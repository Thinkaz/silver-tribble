@props(['thread'])

<div class="card">
    <div class="card-body">
        <div class="text-decoration-none">
            <div class="d-flex justify-content-between">
                <div class="d-flex icon-and-title my-auto">
                    @if($thread->stickied)
                        <div class="d-inline my-auto {{ 'mr-1' }}" data-tippy-content="@lang('cosmo.forums.threads.pinned')">
                            <span class="fa-stack fa-1x">
                                <i class="fas fa-circle fa-stack-2x" style="color: var(--Accent_Color); opacity: .10"></i>
                                <i class="fad fa-thumbtack fa-stack-1x" style="color: var(--Accent_Color); font-size: 1.455rem"></i>
                            </span>
                        </div>
                    @endif
                    @if($thread->locked)
                        <div class="d-inline my-auto {{ $thread->stickied ?? 'ml-1' }} mr-2" data-tippy-content="@lang('cosmo.forums.threads.locked')">
                            <span class="fa-stack fa-1x">
                                <i class="fas fa-circle fa-stack-2x" style="color: #d63031; opacity: .10"></i>
                                <i class="fad fa-lock-alt fa-stack-1x" style="color: #d63031; font-size: 1.255rem"></i>
                            </span>
                        </div>
                    @endif

                    <a class="ml-2 d-inline-block my-auto" href="{{route('forums.threads.show', $thread->id)}}">
                        <p class="mb-0 mt-0 title">{{$thread->title}}</p>
                        <small class="username_full text-muted">
                            @if ($thread->posts_count > 0)
                                @lang('cosmo.forums.replies'): {{ $thread->posts_count }}
                            @else
                                @lang('cosmo.forums.no_reply')
                            @endif
                        </small>
                    </a>
                </div>

                @if($latestPost = $thread->latestPost)
                    <a class="d-inline-flex my-auto" href="{{route('users.show', $latestPost->user->steamid)}}">
                        <span class="mr-2 username_full my-auto text-right">
                            <span data-tippy-content="@lang('cosmo.forums.latest_poster')" style="color: {{ $latestPost->user->displayRole->color  }}">{{ $latestPost->user->username }}</span>
                            <br>
                            <sup>
                                {{ $latestPost->created_at->diffForHumans() }}
                            </sup>
                        </span>
                        <img data-tippy-content="View Profile"
                             src="{{$latestPost->user->avatar}}" class="rounded my-auto" style="max-height: 40px;"
                             alt="Author Profile Picture">
                    </a>
                @else
                    <a class="d-inline-flex my-auto" href="{{route('users.show', $thread->user->steamid)}}">
                        <span class="mr-2 username_full my-auto text-right">
                            <span data-tippy-content="@lang('cosmo.forums.original_author')" style="color: {{ $thread->user->displayRole->color  }}">{{ $thread->user->username }}</span>
                            <br>
                            <sup>
                                {{ $thread->created_at->diffForHumans() }}
                            </sup>
                        </span>
                        <img data-tippy-content="View Profile"
                             src="{{$thread->user->avatar}}" class="rounded my-auto" style="max-height: 40px;"
                             alt="Author Profile Picture">
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>