@extends('themes.havart.layout')

@section('title')
    @lang('cosmo.core.forums')
@endsection

@section('header_title')
    <h1 class="title">{{ $configs['forums_title'] }}</h1>
    <h3 class="subtitle">{{ $configs['forums_description'] }}</h3>
@endsection

@section('crumbs')
    <li class="breadcrumb-item"><a href="{{ route('forums.index') }}">@lang('cosmo.core.home')</a></li>
@endsection

@section('content')
    @include('themes.havart.includes.header')

    <div class="forum-svg-1">
        <img src="{{ asset('themes/havart/img/svgs/f_circle.svg') }}" alt="svgs" class="img-fluid">
    </div>

    <div class="container mt-4" id="forums">
        @include('themes.havart.forums.breadcrumb')

        <div class="svg-3">
            <img src="{{asset('themes/havart/img/svgs/elipsis8x4.svg')}}" alt="svgs" class="img-fluid">
        </div>

        <div class="row justify-content-between">
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
                                            {{-- TODO : Make the username clickable and link to user's profile --}}
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
                                           placeholder="Type your message here.."
                                           x-model="message"/>

                                    <x-base.button>
                                        <x-slot name="type">submit</x-slot>
                                        <x-slot name="style">btn-accent</x-slot>
                                        <x-slot name="icon">fad fa-paper-plane</x-slot>
                                    </x-base.button>
                                </form>
                            @endauth
                        </div>
                    </div>
                @endif

                @foreach($categories as $category)
                        <div class="card mb-4" id="category">
                            <div class="card-header">
                                <div>
                                    <h5 class="card-title mb-0 pb-0">{{ $category->name }}</h5>
                                    <p class="card-desc mb-0 pb-0 text-truncate d-block">{{ $category->description }}</p>
                                </div>
                            </div>
                            <div class="p-2 mb-0">
                                <x-havart.category :main="$category"/>
                            </div>
                        </div>
                @endforeach
            </div>

            @include('includes.forums.stats')
        </div>
        <div class="svg-4">
            <img src="{{asset('themes/havart/img/svgs/d-circle.svg')}}" alt="svgs" class="img-fluid">
        </div>
    </div>
@endsection

@push('meta')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('js/chatbox.js') }}"></script>
@endpush