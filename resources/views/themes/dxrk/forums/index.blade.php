@extends('themes.dxrk.layout')

@section('title')
    @lang('cosmo.core.forums')
@endsection

@section('header')
    <h4 class="section-subheader">{{$configs['forums_description']}}</h4>
    <h2 class="section-header">{{$configs['forums_title']}}</h2>
@endsection

@section('content')
    @include('themes.dxrk.includes.hero')

    <div class="container mt-4" id="forumBoards">

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
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0 pb-0">{{$category->name}}</h5>
                            <p class="card-desc mb-0 pb-0">{{$category->description}}</p>
                        </div>
                        <div class="card-body">
                            @foreach($category->boards as $board)
                                <div class="card board mb-0 p-2" id="board">
                                    <div class="row justify-content-between">
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-1 d-flex justify-content-center mr-2">
                                                    <div class="icon_holder my-auto">
                                                        <div class="container">
                                                            <i class="{{$board->icon}} fa-2x"
                                                               style="color: {{$board->color}}; float: left;"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a class="col-10 info my-auto" style="margin-top: 5px !important;"
                                                   href="{{route('forums.boards.show', $board->id)}}">
                                                    <div class="container content">
                                                        <h5 class="title mb-0 pb-0 name">{{$board->name}}</h5>
                                                        <p class="description mb-0 pb-0 text-truncate text-white-50">{{ truncate($board->description, 70) }}</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-md-3 latest_info">
                                            <div class="justify-content-end text-right d-flex my-auto" id="latest">
                                                @if($board->latestThread)
                                                    <div class="my-auto overflow-hidden text-nowrap">
                                                        <a href="{{ route('forums.threads.show', $board->latestThread->id) }}"
                                                           class="thread_title my-auto d-block">{{ truncate($board->latestThread->title, 13) }}</a>
                                                        <a href="{{ route('users.show', $board->latestThread->user->steamid) }}"
                                                           class="thread_author my-auto d-block">- {{$board->latestThread->user->username}}</a>
                                                    </div>
                                                    <a href="{{ route('users.show', $board->latestThread->user->steamid) }}">
                                                        <img src="{{$board->latestThread->user->avatar}}"
                                                             class="d-none d-lg-block rounded-circle ml-2 profile-forum-image my-auto"
                                                             alt="Thread User Image" style="width: 50px; height: 50px">
                                                    </a>
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

