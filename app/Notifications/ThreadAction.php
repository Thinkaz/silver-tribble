<?php

namespace App\Notifications;

use App\Models\Forums\Thread;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ThreadAction extends Notification
{
    use Queueable;

    private Thread $thread;
    private User $admin;
    private string $action;

    /**
     * Create a new notification instance.
     *
     * @param Thread $thread
     * @param User $admin
     * @param string $action
     */
    public function __construct(Thread $thread, User $admin, string $action)
    {
        $this->thread = $thread;
        $this->admin = $admin;
        $this->action = $action;
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
            'thread_title' => $this->thread->title,
            'action' => $this->action,
            'admin' => $this->admin->username,
            'url' => route('forums.threads.show', $this->thread->id)
        ];
    }
}
