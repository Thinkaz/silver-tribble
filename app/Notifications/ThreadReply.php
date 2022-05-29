<?php

namespace App\Notifications;

use App\Models\Forums\Post;
use Illuminate\Notifications\Notification;

class ThreadReply extends Notification
{
    private Post $post;

    /**
     * Create a new notification instance.
     *
     * @param $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'post_id' => $this->post->id,
            'username' => $this->post->user->username,
            'url' => route('forums.posts.show', $this->post->id)
        ];
    }
}
