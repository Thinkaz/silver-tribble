<?php

namespace App\Importers;

use App\Contracts\Importer;
use App\Events\RoleAssigned;
use App\Models\Forums\Board;
use App\Models\Forums\Category;
use App\Models\Forums\Thread;
use App\Models\Role;
use App\Models\User;
use ErrorException;
use Genert\BBCode\BBCode;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

class MyBBImporter implements Importer
{
    private ConnectionInterface $connection;
    private BBCode $parser;

    private array $usersMap = [];
    private array $categoryMap = [];
    private array $boardsMap = [];

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->parser = new BBCode();

        $this->parser->addParser(
            'color',
            '/\[color\=(.*?)\](.*?)\[\/color\]/s',
            '<span style="color: $1;">$2</span>',
            '$1'
        );

        $this->parser->addParser(
            'size',
            '/\[size\=(.*?)\](.*?)\[\/size\]/s',
            '<span style="font-size: $1;">$2</span>',
            '$1'
        );

        $this->parser->addParser(
            'font',
            '/\[font\=(.*?)\](.*?)\[\/font\]/s',
            '<span style="font-family: $1;">$2</span>',
            '$1'
        );
    }

    /**
     * @throws Throwable
     */
    public function handle()
    {
        DB::transaction(function () {
            $this->connection->table('mybb_forums')
                ->orderBy('fid')
                ->get()->each(function ($forum) {
                    $this->importForum($forum);
                });

            $this->connection->table('mybb_threads')
                ->get()->each(function ($thread) {
                    $this->importThread($thread);
                });
        });
    }

    private function importForum(object $forum)
    {
        // This means the forum is a category
        if ($forum->type === 'c') {
            $category = Category::create([
                'name' => $forum->name,
                'description' => !empty($forum->description) ? $forum->description : null
            ]);

            $this->categoryMap[$forum->fid] = $category;
            return;
        }

        if ($forum->type === 'f') {
            /** @var Board $board */
            $board = Board::make([
                'name' => $forum->name,
                'description' => !empty($forum->description) ? $forum->description : null,
                'icon' => 'fad fa-comments',
                'color' => config('cosmo.configs.site_color'),
                'roles' => []
            ]);

            // Parent count explanation
            // 1: The forum is a category
            // 2: The forum is not a sub board, but a part of category
            // 3 or more: The forum is a sub board

            $parents = explode(',', $forum->parentlist);
            if (count($parents) >= 3) {
                $parent = $this->boardsMap[$forum->pid];

                $board->category_id = $parent->category_id;
                $board->parent_id = $parent->id;
            } else {
                $board->category_id = $this->categoryMap[$forum->pid]->id;
            }

            $board->save();
            $this->boardsMap[$forum->fid] = $board;
        }
    }

    private function importThread(object $thread)
    {
        /** @var object $post */
        $post = $this->connection->table('mybb_posts')
            ->where('pid', $thread->firstpost)
            ->first();

        if (!$post) return;

        $user = $this->importUser($thread->uid);
        if (!$user) return;

        $penis = str_replace(['"d', '&'], ['&quot;', '&amp;'], $this->parser->convertToHtml($post->message));

        /** @var Thread $thread */
        $thread = Thread::make([
            'title' => $thread->subject,
            'stickied' => $thread->sticky,
            'closed' => !empty($thread->closed) ? $thread->closed : false,
            'user_id' => $user->id,
            'board_id' => $this->boardsMap[$thread->fid]->id
        ]);

        try {
            $thread->fill([
                'content' => $penis
            ]);
        } catch (ErrorException $e) {
            $thread->fill([
                'content' => '<p>Failed to import this thread</p>'
            ]);
        }

        $thread->save();
    }

    private function importUser(int $id)
    {
        if (isset($this->usersMap[$id])) {
            return $this->usersMap[$id];
        }

        /** @var object $user */
        $user = $this->connection->table('mybb_users')
            ->where('uid', $id)
            ->first();

        if (!$user) return false;

        preg_match('/(\d{17,})@steamcommunity\.com/s', $user->email, $matches);

        if (!$steamId = $matches[1] ?? null) {
            return false;
        }

        return $this->usersMap[$id] = User::firstOrCreate(
            [
                'steamid' => $steamId,
            ],
            [
                'username' => $user->username,
                'avatar' => $user->avatar,
            ]
        );
    }
}