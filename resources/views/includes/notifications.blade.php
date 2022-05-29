@forelse($notifications as $notification)
    <a href="{{route('notifications.show', $notification->id)}}" class="notification_list_item rounded mb-1">
        <h4 class="notification_title">
            @if(!$notification->read_at)
                <span class="badge badge-accent">
                    <i class="fad fa-star"></i>
                    @lang('cosmo.notifications.new_notif')
                </span>
            @else
                <span class="badge badge-secondary">
                    <i class="fad fa-eye"></i>
                    @lang('cosmo.notifications.viewed')
                </span>
            @endif

            @switch($notification->type)
                @case('App\Notifications\ThreadReply')
                    {{ trans('cosmo.notifications.reply_to_thread', ['username' => $notification->data['username']]) }}
                    @break

                @case('App\Notifications\AchievementUnlocked')
                    {{ trans('cosmo.notifications.unlocked_achievement', ['achievement' => $notification->data['name']]) }}
                    @break

                @case('App\Notifications\ProfileComment')
                    {{ trans('cosmo.notifications.profile_comment', ['username' => $notification->data['username']]) }}
                    @break

                @case('App\Notifications\ProfileLike')
                    {{ trans('cosmo.notifications.profile_like', ['username' => $notification->data['username'], 'state' => $notification->data['state'] ? 'liked' : 'unliked']) }}
                    @break

                @case('App\Notifications\ThreadAction')
                    {{ trans('cosmo.notifications.thread_action', ['admin' => $notification->data['admin'], 'action' => $notification->data['action'], 'title' => $notification->data['thread_title']]) }}
                    @break
            @endswitch
        </h4>
        <h6 class="notification_time">
            {{$notification->created_at->diffForHumans()}}
        </h6>
    </a>
@empty
    <p class="text-muted mb-0">
        @lang('cosmo.notifications.no_notifications')
    </p>
@endforelse