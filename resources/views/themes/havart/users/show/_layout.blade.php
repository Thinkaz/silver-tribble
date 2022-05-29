@extends('themes.havart.layout')

@section('title', $user->username)

@push('meta')
    <style>
        .header_full {
            @if(isset($user->profile->background_img))
                background: linear-gradient(180deg, rgba(28, 28, 33, 0.70) 34%, rgba(28, 28, 33, 0.85) 50%, rgba(28, 28, 33, 1) 100%), url({{ proxy_image($user->profile->background_img) }}) !important;
                background-size: cover !important;
                background-repeat: no-repeat !important;
                background-position: center !important;
            @else
                 background: linear-gradient(to right, #4e73df 10%, #224abe 100%) !important;
            @endif
        }
    </style>
@endpush

@section('header_title')
    <h1 class="title overflow-hidden text-nowrap">{{ $user->username }}</h1>
    <h3 class="subtitle">@lang('cosmo.users.welcome_to_profile')</h3>
@endsection

@section('content')
    @include('themes.havart.includes.header')

    <div class="container p-3" id="users_show">
        <div class="d-flex mb-3">
            <div class="d-inline my-auto">
                <a href="https://steamcommunity.com/profiles/{{$user->steamid}}" target="_blank">
                    <img src="{{$user->avatar}}" alt="User Avatar" class="rounded img-fluid" height="90" width="90">
                </a>
            </div>
            <div class="d-inline-flex flex-grow-1 ml-2">
                <div class="text">
                    <h1 class="profile-name">
                        {{$user->username}}
                    </h1>
                    <h5>
                        @if ($user->displayRole)
                            <span class="badge user-role" style="background-color: {{$user->displayRole->color}}">{{$user->displayRole->display_name}}</span>
                        @endif
                        <span class="badge" style="background-color: {{ ($user->reputation_avg_rating > 0) ? 'var(--Accent_Color);' : 'var(--dark);' }}">
                            @if ($user->reputation_avg_rating > 0 && $user->reputation_avg_rating <= 3)
                                <i class="fad fa-circle-star"></i> Reputation: {{ $user->reputation_avg_rating }}
                            @elseif ($user->reputation_avg_rating > 3)
                                <i class="fad fa-comet"></i> Reputation: {{ $user->reputation_avg_rating }}
                            @else
                                <i class="fal fa-star"></i> No rep
                            @endif
                        </span>
                    </h5>
                </div>
            </div>
            <div class="d-inline-flex ml-auto flex-shrink-0">
                <div class="likes mt-3">
                    <p class="d-inline" onclick="giveLike('{{ $user->steamid }}')">
                        <i class="fas fa-heart {{ $user->profile->likes()->where('user_id', auth()->id())->first() ? 'text-danger' : 'text-muted' }} {{ $user->profile->likes_count >= 10 ? 'fa-gold_anim' : '' }}"></i>
                    </p>
                    <span class="text-muted" id="likes-amount">{{ $user->profile->likes()->count() ?? '0' }}</span>
                </div>
            </div>
        </div>

        <ul class="nav nav-pills mt-4 justify-content-center">
            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0 {{ Route::currentRouteName() === 'users.show' ? 'active' : '' }}"
                   href="{{ route('users.show', $user->steamid) }}">
                    <i class="fad fa-home mr-2"></i>@lang('cosmo.users.pills.home')
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link mb-sm-3 mb-md-0 {{ Route::currentRouteName() === 'users.show.comments' ? 'active' : '' }}"
                   href="{{ route('users.show.comments', $user->steamid) }}">
                    <i class="fad fa-message-lines mr-2"></i>@lang('cosmo.users.pills.comments')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0 {{ Route::currentRouteName() === 'users.show.threads' ? 'active' : '' }}"
                   href="{{ route('users.show.threads', $user->steamid) }}">
                    <i class="fad fa-comment-alt-dots mr-2"></i>@lang('cosmo.users.pills.threads')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0 {{ Route::currentRouteName() === 'users.show.achievements' ? 'active' : '' }}"
                   href="{{ route('users.show.achievements', $user->steamid) }}">
                    <i class="fad fa-trophy-alt mr-2"></i>
                    @lang('cosmo.users.pills.achievements')
                </a>
            </li>

            @can('viewStoreStatistics', $user->profile)
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 {{ Route::currentRouteName() === 'users.show.store' ? 'active' : '' }}"
                       href="{{ route('users.show.store', $user->steamid) }}">
                        <i class="fad fa-chart-line mr-2"></i>
                        @lang('cosmo.users.pills.storeStats')
                    </a>
                </li>
            @endcan

            @can('update', $user->profile)
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 {{ Route::currentRouteName() === 'users.edit' ? 'active' : '' }}"
                       href="{{ route('users.edit', $user->steamid) }}">
                        <i class="fad fa-user-edit mr-2"></i>@lang('cosmo.users.pills.edit')
                    </a>
                </li>
            @endcan
        </ul>

        <div class="mt-5">
            @yield('profile-content')
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/likes.js') }}"></script>
@endpush
