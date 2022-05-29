<?php

namespace Database\Seeders;

use App\Models\Forums\Board;
use App\Models\Forums\Category;
use App\Models\Forums\Thread;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->createMany([
            [
                'id' => 1,
                'name' => 'Important',
                'description' => 'New, announcements and rules'
            ],
            [
                'id' => 2,
                'name' => 'General',
                'description' => 'Chit chat'
            ]
        ]);

        Board::factory()->createMany([
            [
                'category_id' => 1,
                'name' => 'Announcements',
                'description' => 'Keep up with the latest announcements!',
                'icon' => 'fad fa-bullhorn',
                'color' => '#d63031',
            ],
            [
                'category_id' => 1,
                'name' => 'Rules',
                'description' => 'Read our rules to make sure you don\'t get punished!',
                'icon' => 'fad fa-clipboard-list',
                'color' => '#d63031',
            ],
            [
                'category_id' => 2,
                'name' => 'General Chat',
                'description' => 'Talk about anything related to our community!',
                'icon' => 'fad fa-comment',
                'color' => '#0984e3',
            ],
            [
                'category_id' => 2,
                'name' => 'Introductions',
                'description' => 'Introduce yourself into the community!',
                'icon' => 'fad fa-people-carry',
                'color' => '#0984e3',
            ],
        ]);

        Thread::factory()->create([
            'board_id' => 1,
            'user_id' => 1,
            'title' => 'Welcome!',
            'content' => '<p>Welcome to Cosmo :D</p>',
            'locked' => false,
            'stickied' => true
        ]);
    }
}
