@extends('themes.purity.layout')

@section('title')
    @lang('cosmo.core.forums')
@endsection

@section('page_titles')
    <h3 class="subtitle text-truncate">{{ $configs['forums_description'] }}</h3>
    <h1 class="title text-truncate">{{ $configs['forums_title'] }}</h1>
@endsection

@section('crumbs')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('cosmo.core.home')</a></li>
    <li class="breadcrumb-item"><a href="{{ route('forums.index') }}">@lang('cosmo.core.forums')</a></li>
@endsection

@section('content')
    <div class="container" id="forums">

        @include('themes.purity.forums.breadcrumb')
        <div class="row">
            <div class="col-12 col-md-9">

                @if (config('cosmo.configs.forums_chatbox_enabled'))
                    <div id="chatbox" class="card mb-4" x-data="chatbox" style="z-index: 3232;">
                        <div class="card-header">
                            <h5 class="card-title mb-0 pb-0">Chatbox</h5>
                        </div>

                        <div class="card-body d-block">
                            <div class="fixed-height" x-ref="box">
                                <template x-for="msg in messages" :key="msg.id">
                                    <div class="mb-2 d-flex pr-2">
                                        <div class="mr-2 flex-shrink-0">
                                            <img :src="msg.user.avatar" alt="User Avatar"
                                                 class="avatar rounded-circle img-fluid" src=""/>
                                        </div>

                                        <div class="my-auto">
                                            <p class="username" x-text="msg.user.username"
                                               :style="{ color: msg.user.displayRole.color }"></p>
                                            <p class="content" x-text="msg.message"></p>
                                        </div>
                                        <div class="timestamp ml-auto mb-auto flex-shrink-0">
                                            <p x-text="msg.created_at"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            @auth
                                <form @submit.prevent="sendMessage" class="d-flex mt-3">
                                    <label for="text"></label>
                                    <input type="text" id="text" class="form-control flex-fill"
                                           placeholder="Send Message.."
                                           x-model="message"/>

                                    <button type="submit" class="btn btn-primary flex-shrink-0">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                @endif

                @foreach($categories as $category)
                    <div class="card mb-4 category">
                        <div class="card-header gradient_bottom">
                            <h5 class="card-title mb-0 pb-0">{{$category->name}}</h5>
                            <p class="card-desc mb-0 pb-0 text-truncate">{{$category->description}}</p>
                        </div>
                        <div class="justify-content-between p-2 mb-0">
                            @foreach($category->boards as $board)
                                <div class="card mb-1 p-2 board border-0">
                                    <div class="row justify-content-between">
                                        <div class="col-md-9">
                                            <a href="{{route('forums.boards.show', $board->id)}}">
                                                <div class="row">
                                                    <div class="col-1 mr-1 pl-0 my-auto">
                                                        <div class="text-center icon-holder">
                                                            <span class="fa-stack fa-2x">
                                                              <i class="fas fa-square fa-stack-2x"
                                                                 style="color: {{$board->color}}"></i>
                                                              <i class="{{$board->icon}} fa-stack-1x fa-inverse"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-9 my-auto media-f"
                                                         style="margin-top: 5px !important; padding-left: 0;">
                                                        <div class="info">
                                                            <h5 class="title mb-0 pb-0">{{$board->name}}</h5>
                                                            <p class="description mb-0 pb-0 text-truncate">{{ truncate($board->description, 70) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        @if($latestThread = $board->latestThread)
                                            <div class="col-md-3">
                                                <div class="container pr-0 my-auto latest_info">
                                                    <div class="justify-content-end text-right d-flex my-auto text-truncate"
                                                         style="margin-top: 5px !important;">
                                                        <div class="my-auto">
                                                            <a href="{{ route('forums.threads.show', $latestThread->id) }}">
                                                                <h5 class="thread_title my-auto">{{ truncate($latestThread->title, 13) }}</h5>
                                                            </a>
                                                            <a href="{{ route('users.show', $latestThread->user->steamid) }}">
                                                                <p class="thread_author my-auto">
                                                                    - {{$latestThread->user->username}}</p>
                                                            </a>
                                                        </div>
                                                        <div class="my-auto">
                                                            <a href="{{ route('users.show', $latestThread->user->steamid) }}">
                                                                <img src="{{$latestThread->user->avatar}}"
                                                                     class="d-none d-lg-block rounded-circle ml-2 my-auto thread_avatar"
                                                                     alt="Thread User Image"
                                                                     style="width: 50px; height: 50px">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            @include('includes.forums.stats')
        </div>
    </div>
@endsection


@push('style')
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

@push('scripts')
    <script src="{{ asset('js/chatbox.js') }}"></script>
@endpush