<?php

namespace App\Importers;

use App\Contracts\Importer;
use App\Models\Forums\Board;
use App\Models\Forums\Category;
use App\Models\Forums\Post;
use App\Models\Forums\Thread;
use App\Models\Index\Feature;
use App\Models\Index\NavLink;
use App\Models\Index\Server;
use App\Models\User;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\s;

class BablSuiteImporter implements Importer
{
    private ConnectionInterface $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function handle()
    {
        DB::transaction(function () {
            $this->importNavlinks();
            $this->importBiglinks();
            $this->importFeatures();
            $this->importServers();

            $this->importForums();
        });
    }

    protected function importNavlinks()
    {
        $links = $this->connection->table('nav_links')
            ->get()->map(function ($link) {
                return [
                    'name' => $link->title,
                    'url' => $link->url,
                    'color' => $link->color ?? '#3498db'
                ];
            })->toArray();

        NavLink::insert($links);
    }

    protected function importBiglinks()
    {
        $bigLinks = $this->connection->table('big_links')
            ->get()->map(function ($link) {
                return [
                    'name' => $link->title,
                    'url' => $link->url,
                    'icon' => $link->fa_icon,
                    'color' => $link->fa_color,
                    'category' => 'Big Link'
                ];
            })->toArray();

        NavLink::insert($bigLinks);
    }

    protected function importFeatures()
    {
        $features = $this->connection->table('features')
            ->get()->map(function ($feature) {
                return [
                    'name' => $feature->title,
                    'icon' => $feature->fa_icon,
                    'color' => $feature->fa_color,
                    'content' => $feature->description
                ];
            })->toArray();

        Feature::insert($features);
    }

    protected function importServers()
    {
        $this->connection->table('serverquery')
            ->get()->map(function ($server) {
                return [
                    'name' => $server->name,
                    'icon' => $server->fa_icon,
                    'color' => $server->fa_color,
                    'description' => $server->description,
                    'image' => $server->image,
                    'ip' => $server->ip,
                    'port' => $server->port
                ];
            })->each(fn($server) => Server::create($server));
    }

    private function importForums()
    {
        /** @var array<int, Category> $categoriesMap */
        $categoriesMap = [];

        $this->connection->table('categories')->each(function (object $category) use (&$categoriesMap) {
            $cat = Category::create([
                'name' => $category->name,
                'description' => $category->description,
            ]);

            $categoriesMap[$category->id] = $cat;
        });

        /** @var array<int, Board> $boardsMap */
        $boardsMap = [];

        $this->connection->table('boards')->each(function (object $board) use (&$boardsMap, $categoriesMap) {
            $category = $categoriesMap[$board->category_id];
            if (!$category) return;

            $newBoard = Board::make([
                'name' => $board->name,
                'description' => $board->description,
                'icon' => $board->fa_icon,
                'color' => $board->fa_color,
                'roles' => [],
            ]);

            $newBoard->category()->associate($category);
            $newBoard->save();

            $boardsMap[$board->id] = $newBoard;
        });

        /** @var array<int, Thread> $threadsMap */
        $threadsMap = [];

        /** @var array<int, User> $usersMap */
        $usersMap = [];

        $this->connection->table('threads')->each(function (object $thread) use (&$threadsMap, &$usersMap, $boardsMap) {
            $board = $boardsMap[$thread->board_id];
            if (!$board) return;

            if ($usersMap[$thread->user_steamid]) {
                $user = $usersMap[$thread->user_steamid];
            } else {
                $userData = $this->connection->table('users')->where('steamid', $thread->user_steamid)->first();

                $user = User::firstOrCreate([
                    'steamid' => $thread->user_steamid,
                ], [
                    'username' => $userData->username,
                    'avatar' => $userData->avatar,
                ]);
            }

            $newThread = Thread::make([
                'title' => $thread->title,
                'content' => $thread->text,
                'locked' => $thread->locked,
                'stickied' => $thread->stickied,
            ]);

            $newThread->user()->associate($user);
            $newThread->board()->associate($board);
            $newThread->save();

            $threadsMap[$thread->id] = $newThread;
        });

        $this->connection->table('replies')->each(function (object $reply) use ($threadsMap, $usersMap) {
            $thread = $threadsMap[$reply->thread_id];
            if (!$thread) return;

            if ($usersMap[$reply->user_steamid]) {
                $user = $usersMap[$reply->user_steamid];
            } else {
                $userData = $this->connection->table('users')->where('steamid', $reply->user_steamid)->first();

                $user = User::firstOrCreate([
                    'steamid' => $reply->user_steamid,
                ], [
                    'username' => $userData->username,
                    'avatar' => $userData->avatar,
                ]);
            }

            $post = Post::make([
                'content' => $reply->text,
            ]);

            $post->thread()->associate($thread);
            $post->user()->associate($user);

            $post->save();
        });
    }
}