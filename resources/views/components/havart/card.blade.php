@props(['data'])

@if ($type == 'feature')
    <div class="feat card h-100 text-center d-flex {{ $animation ?? "" }}" style="{{ $globalStyle ?? "" }}">
        <div class="w-100 spanner d-flex pt-3">
            <span class="fa-stack fa-2x d-inline-flex ml-2">
                <i class="fas {{ $backIcon ?? 'fa-square' }} fa-stack-2x"></i>
                <i class="fad {{ $icon ?? 'fa-paint-brush' }} fa-stack-1x"></i>
            </span>
            <h3 class="card-title d-inline-flex my-auto ml-auto mr-4">{{ $title }}</h3>
        </div>
        <div class="card-body">
            <span class="content">{{ $slot }}</span>
        </div>
    </div>
@elseif ($type == 'profile')
    <div class="card {{ $animation ?? "" }}" style="{{ $globalStyle ?? "" }}">
        <div class="card-header pb-0">
            <h3 class="card-title">{{ $title ?? "Title" }}</h3>
        </div>
        <div class="card-body">
            {{ $slot }}
            <span class="fa-stack fa-2x">
                <i class="fas {{ $backIcon ?? 'fa-square' }} fa-stack-2x"></i>
                <i class="fad {{ $icon ?? 'fa-paint-brush' }} fa-stack-1x"></i>
            </span>
        </div>
    </div>
@elseif ($type == 'steam')
    <div class="card h-100 border-0 stat {{ $animation ?? "" }}" style="{{ $globalStyle ?? "" }}">
        <h5 class="card-title purecounter" data-purecounter-duration="2.5"
            data-purecounter-separator="true" data-purecounter-separatorsymbol=","
            data-purecounter-end="{{ $slot }}">~~~</h5>
        <p class="{{ $steamClass ?? "text-muted" }}">
            {{ $steamStr ?? "Placeholder" }}
        </p>
    </div>
@elseif ($type == 'user')
    <a class="card leader h-100 border-0 shadow" href="{{ route('users.show', $data->steamid) }}">
        <div class="card-header" style="background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url({{ $data->profile->background_img }}); background-size: contain, cover"></div>
        <div class="card-body">
            <div class="content d-flex text-nowrap overflow-hidden">
                <img src="{{ $data->avatar }}" alt="" class="img-fluid rounded-circle d-inline mr-2">
                <div>
                    <h3 class="card-title d-inline text-white">{{ $data->username }}</h3>
                    <h3 class="role mt-0" style="color: {{ $data->displayRole->color }}">{{ $data->displayRole->display_name }}</h3>
                </div>
            </div>
        </div>
    </a>
@elseif ($type == 'statforum')
    <div class="card mb-4 stat-card-forum" id="{{ $id }}">
        <div class="card-header gradient_bottom">
            <i class="fad {{ $icon ?? "" }}"></i>
            <h5 class="card-title mb-0 pb-0 text-truncate">{{ $title ?? "" }}</h5>
            <p class="mb-0 pb-0 card-desc pt-0 text-truncate">{{ $description ?? "" }}</p>
        </div>
        @if ($id == 'forum_polls')
            <form action="{{route('forums.polls.store', $data->id)}}" method="POST">@csrf
        @endif
        <div class="card-body" id="latest_threads">
            @if ($id == 'search_threads')
                <form action="{{route('forums.threads.search')}}" method="post">
                    @csrf
                    <input type="text" name="search" placeholder="@lang('cosmo.forums.search_placeholder')" class="form-control" value="" aria-label="Search input">
                </form>
            @elseif ($id == 'latest_posts')
                @foreach($data as $post)
                    <div class="d-flex mb-3">
                        <div class="h-100" style="margin-top: 0.3rem;">
                            <a href="{{ route('users.show', $post->user->steamid) }}">
                                <img src="{{ $post->user->avatar }}" alt="User Avatar" class="rounded-lg"
                                     width="36" height="36">
                            </a>
                        </div>
                        <div class="flex-1 ml-2 overflow-hidden text-nowrap" style="text-overflow: ellipsis; color: #ccc">
                            <a href="{{ route('forums.posts.show', $post->id) }}"
                               class="font-weight-bold" style="font-size: 15px; color: #ccc;">{{ $post->thread->title }}
                            </a>
                            <div class="text-muted" style="font-size: 13px;">
                                <div class="d-inline">
                                    {{ $post->created_at->diffForHumans() }}
                                </div>
                                by
                                <a href="{{ route('users.show', $post->user->steamid) }}" class="text-decoration-none" style="color: {{ $post->user->displayRole->color }};">
                                    {{ $post->user->username }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @elseif ($id == 'latest_threads')
                @foreach($data as $thread)
                    <div class="d-flex mb-3">
                        <div class="h-100" style="margin-top: 0.3rem;">
                            <a href="{{ route('users.show', $thread->user->steamid) }}">
                                <img src="{{ $thread->user->avatar }}" alt="User Avatar" class="rounded-lg"
                                     width="36" height="36">
                            </a>
                        </div>
                        <div class="flex-1 ml-2 overflow-hidden text-nowrap" style="text-overflow: ellipsis; color: #ccc">
                            <a href="{{ route('forums.threads.show', $thread->id) }}"
                               class="font-weight-bold" style="font-size: 15px; color: #ccc;">
                                {{ $thread->title }}
                            </a>
                            <div class="text-muted" style="font-size: 13px;">
                                <div class="d-inline">
                                    {{ $thread->created_at->diffForHumans() }}
                                </div>
                                by
                                <a href="{{ route('users.show', $thread->user->steamid) }}"
                                   class="text-decoration-none" style="color: {{ $thread->user->displayRole->color }};">
                                    {{ $thread->user->username }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @elseif ($id == 'forum_polls')
                <ul class="polls-group list-unstyled">
                    <h5 class="text-truncate">{{ $data->title }}</h5>
                    <p>{{ $data->description }}</p>

                    @foreach($data->answers as $id => $answer)
                        <li class="group">
                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" class="custom-control-input" id="answer-{{$id}}" name="answer"
                                       value="{{$id}}">
                                <label class="custom-control-label" for="answer-{{$id}}"> {{ $answer }}</label>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                {{ $slot }}
            @endif
        </div>
        @if (isset($footer))
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-accent">
                    @lang('cosmo.actions.submit')
                </button>
            </div>
        </form>
        @endif
    </div>
@endif