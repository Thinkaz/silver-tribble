@extends('themes.havart.layout')

@section('title')
    @lang('cosmo.core.viewing_thread')
@endsection

@section('header_title')
    <h1 class="title text-truncate">{{ $thread->title }}</h1>
@endsection

@section('crumbs')
    <li class="breadcrumb-item"><a href="{{ route('forums.index') }}">@lang('cosmo.core.home')</a></li>

    @foreach($thread->board->breadcrumbs as $breadcrumb)
        <li class="breadcrumb-item">
            <a href="{{ route('forums.boards.show', $breadcrumb->id) }}">
                {{ $breadcrumb->name }}
            </a>
        </li>
    @endforeach

    <li class="breadcrumb-item"><a class="text-truncate"
                                   href="{{ route('forums.threads.show', $thread->id) }}">{{ $thread->title }}</a></li>
@endsection

@section('content')
    @include('themes.havart.includes.header')

    @if ($canMoveThreads)
        <div class="modal fade" id="move-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{route('forums.threads.move', $thread->id)}}" method="post">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">@lang('cosmo.forums.threads.move_thread')</h5>
                        </div>
                        <div class="modal-body">
                            <label for="move-board-id">@lang('cosmo.core.board')</label>
                            <select class="form-control custom-select" id="move-board-id" name="board">
                                @foreach($categories as $category)
                                    <option disabled>-={{$category->name}}=-</option>

                                    @foreach($category->boards as $board)
                                        <option class="text-truncate" value="{{$board->id}}">
                                            {{$board->name}}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                @lang('cosmo.actions.close')
                            </button>
                            <button type="submit" class="btn btn-primary">
                                @lang('cosmo.forums.threads.move_thread')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @if ($canManageThreads || $thread->user->is(auth()->user()))
        <form action="{{route('forums.threads.destroy', $thread->id)}}" method="post">
            @csrf
            @method('DELETE')
            <div class="modal" id="threadConfirmDelete-{{ $thread->id  }}" tabindex="-1"
                 role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle"
                                style="color: black">Delete Thread</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="color: rgba(0,0,0,.75)">
                            Are you sure you wish to delete "{{ $thread->title }}"? <br/>
                            This action is irreversible!
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">Close
                            </button>
                            <button type="submit" class="btn btn-danger"
                                    data-tippy-content="{{ trans('cosmo.forums.threads.delete_thread') }}">DELETE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endcan

    <div class="container mt-4" id="threads">
        @include('themes.havart.forums.breadcrumb')
        <div class="card <?php if ($thread->stickied) { echo "border-left-accent"; } elseif ($thread->locked) { echo "border-left-danger"; } ?> shadow border-0 mb-3 thread">
            <div class="card-header border-bottom-0">
                <div class="row justify-content-between">
                    <div class="col-auto">
                        <h2>
                            @if($thread->stickied)
                                <span class="badge badge-accent text-uppercase"><i class="fad fa-thumbtack"></i> @lang('cosmo.forums.threads.pinned')</span>
                            @endif
                            @if($thread->locked)
                                <span class="badge badge-danger text-uppercase"><i class="fad fa-lock"></i> @lang('cosmo.forums.threads.locked')</span>
                            @endif
                            {{$thread->title}}
                        </h2>
                    </div>

                    <div class="col-auto btn-group">
                        @if($canManageThreads || $thread->user->is(auth()->user()))
                            <form action="{{route('forums.threads.edit', $thread->id)}}" method="get" class="mr-2">
                                @csrf
                                <x-base.button>
                                    <x-slot name="type">submit</x-slot>
                                    <x-slot name="style">btn-outline-accent btn-circle btn-sm</x-slot>
                                    <x-slot name="tooltip">@lang('cosmo.forums.threads.edit_thread')</x-slot>
                                    <x-slot name="icon">fad fa-pencil</x-slot>
                                </x-base.button>
                            </form>
                        @endif
                        @can('sticky', $thread)
                            <form action="{{route('forums.threads.sticky', $thread->id)}}" method="post" class="mr-2">
                                @csrf
                                <x-base.button>
                                    <x-slot name="type">submit</x-slot>
                                    <x-slot name="style">btn-outline-info btn-circle btn-sm</x-slot>
                                    <x-slot name="icon">{{ $thread->stickied ? 'fad fa-circle-xmark' : 'fad fa-thumbtack' }}</x-slot>
                                    <x-slot name="tooltip">{{ $thread->sticked ? trans('cosmo.forums.threads.unpin_thread') : trans('cosmo.forums.threads.pin_thread') }}</x-slot>
                                </x-base.button>
                            </form>
                        @endcan
                        @can('lock', $thread)
                            <form action="{{route('forums.threads.lock', $thread->id)}}" method="post" class="mr-2">
                                @csrf
                                <x-base.button>
                                    <x-slot name="type">submit</x-slot>
                                    <x-slot name="style">btn-outline-warning btn-circle btn-sm</x-slot>
                                    <x-slot name="icon">{{ $thread->locked ? 'fad fa-lock-open-alt' : 'fad fa-lock-alt' }}</x-slot>
                                    <x-slot name="tooltip">{{ $thread->locked ? trans('cosmo.forums.threads.unlock_thread') : trans('cosmo.forums.threads.lock_thread') }}</x-slot>
                                </x-base.button>
                            </form>
                        @endcan
                        @if ($canMoveThreads)
                            <form action="" class="mr-2">
                                <x-base.button>
                                    <x-slot name="modal">#move-modal</x-slot>
                                    <x-slot name="style">btn-outline-success btn-circle btn-sm</x-slot>
                                    <x-slot name="icon">fad fa-exchange</x-slot>
                                    <x-slot name="tooltip">@lang('cosmo.forums.threads.move_thread')</x-slot>
                                </x-base.button>
                            </form>
                        @endif
                        @if($canManageThreads || $thread->user->is(auth()->user()))
                            <form action="">
                                <x-base.button>
                                    <x-slot name="modal">#threadConfirmDelete-{{ $thread->id  }}</x-slot>
                                    <x-slot name="style">btn-outline-danger btn-circle btn-sm</x-slot>
                                    <x-slot name="icon">fad fa-trash</x-slot>
                                    <x-slot name="tooltip">@lang('cosmo.forums.threads.delete_thread')</x-slot>
                                </x-base.button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 mt-auto mb-auto">
                        <div class="content text-center">
                            <img src="{{$thread->user->avatar}}" class="rounded" style="max-height: 80px;" alt="User avatar">
                            <h5 class="mt-1 mb-0" data-tippy-content="{{ $thread->user->displayRole->display_name }}">
                                <a style="color: {{$thread->user->displayRole->color}};" href="{{ route('users.show', $thread->user->steamid) }}">
                                    {{$thread->user->username}}
                                </a>
                            </h5>
                            <h5>
                                <span class="badge" style="background-color: {{ ($thread->reputation_avg_rating > 0) ? 'var(--Accent_Color);' : 'var(--dark);' }}">
                                    @if ($thread->reputation_avg_rating > 0 && $thread->reputation_avg_rating <= 3)
                                        <i class="fad fa-circle-star"></i> Reputation: {{ $thread->reputation_avg_rating }}
                                    @elseif ($thread->reputation_avg_rating > 3)
                                        <i class="fad fa-comet"></i> Reputation: {{ $thread->reputation_avg_rating }}
                                    @else
                                        <i class="fal fa-star"></i> No rep
                                    @endif
                                </span>
                            </h5>

                            <x-base.role-banners :user="$thread->user"/>
                        </div>
                    </div>
                    <div class="col border-left">
                        {!! $thread->content !!}
                        <hr class="mb-3 mt-2" style="background-color: var(--secondary); width: auto; border-radius: 20px">
                        @if($thread->user->profile->signature)
                            {!! $thread->user->profile->signature !!}
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer" style="height: 50px">
                <div class="reactions d-flex mb-3 w-100" style="color: rgba(255,255,255,1)">
                    <div class="reacted d-inline-flex flex-grow-1 pl-4">
                        <span class="text-muted ml-2 mr-2" data-tippy-content="{{ $thread->created_at }}"><i class="fad fa-clock pr-1"></i> Posted {{ $thread->created_at->diffForHumans() }}</span>
                        @foreach($thread->reactions as $emoji => $count)
                            <div class="reaction d-flex mr-0">
                                <img src="{{asset($emoji)}}" alt="" class="img-fluid">
                                <small class="mt-auto">X{{ $count }}</small>
                            </div>
                        @endforeach
                    </div>

                    @if($thread->canReact(auth()->id()))
                        <div class="add-reaction d-inline-flex flex-shrink-0 pr-2">
                            @foreach(config('cosmo.reactions') as $id => $reaction)
                                <div class="reaction">
                                    <img reaction-thread data-thread-id="{{$thread->id}}" data-reaction-id="{{$id}}"
                                         src="{{ asset($reaction['emoji']) }}" alt="{{ $reaction['display'] }}"
                                         data-tippy-content="{{$reaction['display']}}" class="img-fluid">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @foreach($posts as $post)
            <div class="card shadow border-0 mb-3 thread" id="post-{{$post->id}}">
                    <!-- <div class="card-body" style="background: no-repeat linear-gradient(180deg, rgba(28, 28, 33, 0.90) 34%, rgba(28, 28, 33, 0.95) 50%, rgba(28, 28, 33, 1) 100%), url({{ proxy_image($post->user->profile->background_img) }}) top !important; background-size: cover !important;"> -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mt-auto mb-auto">
                            <div class="content text-center justify-content-center">
                                <img src="{{$post->user->avatar}}" class="rounded" style="max-height: 80px;" alt="User avatar">
                                <h5 class="mt-1 mb-0" data-tippy-content="{{ $post->user->displayRole->display_name }}">
                                    <a style="color: {{ $post->user->displayRole->color }};" href="{{ route('users.show', $post->user->steamid) }}">
                                        {{$post->user->username}}
                                    </a>
                                </h5>
                                <h5>
                                    <span class="badge" style="background-color: {{ ($post->user->reputation_avg_rating > 0) ? 'var(--Accent_Color);' : 'var(--dark);' }}">
                                        @if ($post->user->reputation_avg_rating > 0 && $post->user->reputation_avg_rating <= 3)
                                            <i class="fad fa-circle-star"></i> Reputation: {{ $post->user->reputation_avg_rating }}
                                        @elseif ($post->user->reputation_avg_rating > 3)
                                            <i class="fad fa-comet"></i> Reputation: {{ $post->user->reputation_avg_rating }}
                                        @else
                                            <i class="fal fa-star"></i> No rep
                                        @endif
                                    </span>
                                </h5>

                                <x-base.role-banners :user="$post->user"/>
                            </div>
                        </div>
                        <div class="col border-left">
                            {!! $post->content !!}
                            @if($post->user->profile->signature)
                                <hr class="mb-3 mt-2" style="background-color: var(--secondary); width: auto; border-radius: 20px">
                                {!! $post->user->profile->signature !!}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer" style="{{ $post->canReact(auth()->id()) ? 'height: 100px' : 'height: 60px' }}">
                    <div class="reactions d-flex mb-3 w-100 mt-0" style="color: rgba(255,255,255,1)">
                        <div class="reacted d-inline-flex flex-grow-1 pl-4 {{ $post->canReact(auth()->id()) ? '' : 'mt-2' }}">
                            <span class="text-muted ml-2 mr-2" data-tippy-content="{{ $post->created_at }}"><i class="fad fa-clock pr-1"></i> Posted {{ $post->created_at->diffForHumans() }}</span>
                            @foreach($post->reactions as $emoji => $count)
                                <div class="reaction d-flex ml-2">
                                    <img src="{{asset($emoji)}}" alt="" class="img-fluid">
                                    <small class="mt-auto">x{{ $count }}</small>
                                </div>
                            @endforeach
                        </div>

                        @if($post->canReact(auth()->id()))
                            <div class="add-reaction d-inline-flex flex-shrink-3 pr-2">
                                @foreach(config('cosmo.reactions') as $id => $reaction)
                                    <div class="reaction">
                                        <img reaction-post data-post-id="{{$post->id}}" data-reaction-id="{{$id}}"
                                             src="{{ asset($reaction['emoji']) }}" alt="{{ $reaction['display'] }}"
                                             data-tippy-content="{{$reaction['display']}}" class="img-fluid">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="thread-actions add-reaction d-inline-flex">
                                @if($canManageThreads || $post->user->is(auth()->user()))
                                    <form action="{{route('forums.posts.edit', $post->id)}}" method="get">
                                        <x-base.button>
                                            <x-slot name="type">submit</x-slot>
                                            <x-slot name="style">btn-accent btn-circle btn-sm mr-2</x-slot>
                                            <x-slot name="icon">fad fa-pencil</x-slot>
                                            @lang('cosmo.forums.threads.edit_reply')
                                        </x-base.button>
                                    </form>

                                    <form action="{{route('forums.posts.destroy', $post->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <x-base.button>
                                            <x-slot name="type">submit</x-slot>
                                            <x-slot name="style">btn-danger btn-circle btn-sm</x-slot>
                                            <x-slot name="icon">fad fa-trash</x-slot>
                                            @lang('cosmo.forums.threads.delete_reply')
                                        </x-base.button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                    @if($post->canReact(auth()->id()))
                        <div class="thread-actions d-flex btn-group justify-content-center pt-1">
                            @if($canManageThreads || $post->user->is(auth()->user()))
                                <form action="{{route('forums.posts.edit', $post->id)}}" method="get" class="mr-2">
                                    <x-base.button>
                                        <x-slot name="type">submit</x-slot>
                                        <x-slot name="style">btn-accent btn-circle btn-sm mr-2</x-slot>
                                        <x-slot name="icon">fad fa-pencil</x-slot>
                                        @lang('cosmo.forums.threads.edit_reply')
                                    </x-base.button>
                                </form>

                                <form action="{{route('forums.posts.destroy', $post->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <x-base.button>
                                        <x-slot name="type">submit</x-slot>
                                        <x-slot name="style">btn-danger btn-circle btn-sm</x-slot>
                                        <x-slot name="icon">fad fa-trash</x-slot>
                                        @lang('cosmo.forums.threads.delete_reply')
                                    </x-base.button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        @can('create', [\App\Models\Forums\Post::class, $thread])
            <div class="card shadow mb-3 thread">
                <div class="card-body">
                    <form action="{{route('forums.posts.store', $thread->id)}}" method="post">
                        @csrf
                        <textarea name="content" aria-label="Content area"></textarea>
                        <x-base.button>
                            <x-slot name="type">submit</x-slot>
                            <x-slot name="style">btn-accent mt-3</x-slot>
                            <x-slot name="icon">fad fa-message-lines</x-slot>
                            @lang('cosmo.actions.post')
                        </x-base.button>
                    </form>
                </div>
            </div>
        @else
            <div class="card border-left-danger shadow mb-5 thread">
                <div class="card-body">
                    <div class="d-inline my-auto">
                        <i class="fad fa-lock-alt fa-2x" style="color: #d63031; font-size: 1.455rem"></i>
                    </div>
                    <div class="d-inline-block ml-2">
                        <h5>@lang('cosmo.forums.threads.locked_no_reply')</h5>
                    </div>
                </div>
            </div>
        @endcan

        {{ $posts->links('themes.havart.pagination') }}
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/reactions.js') }}"></script>
    <script src="{{ asset('js/tinymce.js') }}"></script>
@endpush
