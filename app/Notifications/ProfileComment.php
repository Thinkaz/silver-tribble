<?php

namespace App\Notifications;

use App\Models\Profile\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProfileComment extends Notification
{
    use Queueable;

    private Comment $comment;

    /**
     * Create a new notification instance.
     *
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'username' => $this->comment->user->username,
            'url' => route('users.show', $notifiable->steamid)
        ];
    }
}
