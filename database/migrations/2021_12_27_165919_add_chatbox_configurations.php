<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddChatboxConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('configurations')
            ->insert([
                [
                    'key' => 'forums_chatbox_enabled',
                    'value' => true,
                    'type' => 'boolean',
                    'display_name' => 'Chatbox Enabled',
                    'category' => 'forums.chatbox',
                ],
                [
                    'key' => 'forums_chatbox_word_filter',
                    'value' => true,
                    'type' => 'boolean',
                    'display_name' => 'Chatbox Word Filter Enabled',
                    'category' => 'forums.chatbox',
                ]
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('configurations')
            ->whereIn('key', ['forums_chatbox_enabled', 'forums_chatbox_word_filter'])
            ->delete();
    }
}
