<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProfileLike extends Notification
{
    use Queueable;

    private User $user;
    private bool $state;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param bool $state
     */
    public function __construct(User $user, bool $state)
    {
        $this->user = $user;
        $this->state = $state;
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
            'username' => $this->user->username,
            'state' => $this->state,
            'url' => route('users.show', $this->user->steamid)
        ];
    }
}
