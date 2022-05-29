@extends('themes.lara.layout')

@section('title')
    @lang('cosmo.core.forums')
@endsection


@section('header_title')
    <h3 class="title">{{ $configs['forums_title'] }}</h3>
    <p class="subtitle">{{ $configs['forums_description'] }}</p>
@endsection

@section('crumbs')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('cosmo.core.home')</a></li>
    <li class="breadcrumb-item"><a href="{{ route('forums.index') }}">@lang('cosmo.core.forums')</a></li>
@endsection


@section('content')
    @include('themes.lara.includes.header')
    <div class="container mt-4" id="forums">

        @include('themes.lara.forums.breadcrumb')
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
                    <div class="card mb-4" id="category">
                        <div class="card-header">
                            <h5 class="card-title mb-0 pb-0">{{$category->name}}</h5>
                            <p class="card-desc mb-0 pb-0 text-truncate">{{$category->description}}</p>
                        </div>
                        <div class="justify-content-between p-2 mb-0">
                            @foreach($category->boards as $board)
                                <div class="card mb-0 p-2" id="board">
                                    <div class="row justify-content-between">
                                        <div class="col-md-9 overflow-hidden">
                                            <div class="d-flex">
                                                <div class="d-inline-flex flex-shrink-0 mr-4">
                                                    <div class="icon_holder my-auto">
                                                        <div class="container">
                                                            <i class="{{$board->icon}} fa-2x setWidth"
                                                               style="color: {{$board->color}};"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-inline-flex flex-grow-1 info my-auto">
                                                    <div class="container content">
                                                        <a href="{{route('forums.boards.show', $board->id)}}">
                                                            <h5 class="title mb-0 pb-0">{{$board->name}}</h5>
                                                        </a>
                                                        <p class="description mb-0 pb-0 text-truncate">{{ truncate($board->description, 70) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 latest_info">
                                            <div class="justify-content-end text-right d-flex my-auto" id="latest">
                                                @if($latestThread = $board->latestThread)
                                                    <div class="my-auto overflow-hidden text-nowrap">
                                                        <a href="{{ route('forums.threads.show', $latestThread->id) }}">
                                                            <h5 class="thread_title my-auto">{{ truncate($latestThread->title, 13) }}</h5>
                                                            <p class="thread_author my-auto">
                                                                - {{$latestThread->user->username}}</p>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <img src="{{$latestThread->user->avatar}}"
                                                             class="d-none d-lg-block rounded-circle ml-2 profile-forum-image my-auto"
                                                             alt="Thread User Image" style="width: 50px; height: 50px">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
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
