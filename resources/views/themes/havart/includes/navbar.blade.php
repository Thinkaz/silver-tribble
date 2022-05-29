<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <button class="navbar-toggler ml-auto mb-2" type="button" data-toggle="collapse" data-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarText">
            <a href="{{ route('home') }}#servers" class="btn btn-servers mr-auto mt-2">
                <i class="fad fa-star"></i>
                @lang('cosmo.core.theme_specific.join_servers')
            </a>

            <ul class="navbar-nav {{ !$configs['discord_widget_enabled'] ? 'm-auto' : '' }}">
                @foreach($navlinks as $category => $links)
                    @if (!empty($category))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{$category}}
                            </a>
                            <div class="dropdown-menu drops" aria-labelledby="navbarDropdown">
                                @foreach($links as $link)
                                    <a class="dropdown-item" href="{{ $link->url }}">
                                        <div class="d-flex">
                                            <div class="d-inline my-auto mr-3">
                                                <i class="{{ $link->icon }}" style="color: {{ $link->color }};"></i>
                                            </div>
                                            <div class="d-inline">
                                                {{ $link->name }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @else
                        @foreach($links as $link)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ $link->url }}">{{ $link->name }}</a>
                            </li>
                        @endforeach
                    @endif
                @endforeach

                @if(auth()->check())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" type="button" id="navbarDropdown" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                <img src="{{auth()->user()->avatar}}" class="rounded-circle mr-1"
                                     style="max-height: 20px; max-width: 20px" aria-label="User avatar" alt="User avatar">
                                {{ auth()->user()->username }}
                            </a>
                            <div class="dropdown-menu drops" aria-labelledby="navbar-dropdown">
                                <div class="dropdown_content">
                                    <a class="dropdown-item mb-0" href="{{route('users.show', auth()->user()->steamid)}}">
                                        <div class="d-flex">
                                            <div class="d-inline my-auto mr-2">
                                                <i class="fad fa-user"></i>
                                            </div>
                                            <div class="d-inline">
                                                @lang('cosmo.navbar.profile')
                                            </div>
                                        </div>
                                    </a>

                                    @can('view-management')
                                        <a class="dropdown-item mb-0" href="{{route('manage.dashboard')}}" target="_blank">
                                            <div class="d-flex">
                                                <div class="d-inline my-auto mr-2">
                                                    <i class="fad fa-cog"></i>
                                                </div>
                                                <div class="d-inline">
                                                    @lang('cosmo.navbar.management')
                                                </div>
                                            </div>
                                        </a>
                                    @endcan
                                    <hr class="bg-dark mt-0 mb-1">
                                </div>
                                <div class="dropdown-menu_footer pr-0 pl-0 mb-0" style="background-color: transparent">
                                    <div class="d-flex">
                                        <div class="d-inline-flex mr-auto">
                                            <form action="{{ route('logout') }}" method="post">
                                                @csrf
                                                <button class="dropdown-item" type="submit">
                                                    <i class="fad fa-sign-out-alt text-danger pr-1" style="color: var(--danger) !important;"></i>
                                                    <span class="text-uppercase">
                                                    @lang('cosmo.navbar.logout')
                                                </span>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="d-inline-flex ml-auto">
                                            <a href="{{route('notifications')}}" class="dropdown-item">
                                                {{ auth()->user()->unreadNotifications->count() > 0 ? auth()->user()->unreadNotifications->count() : '' }}
                                                <i class="fad fa-bell" style="{{ auth()->user()->unreadNotifications->count() > 0 ? 'color: var(--warning);' : '' }}"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <div class="dropdown my-auto ml-1">
                    </div>
                @else
                    <li class="nav-item my-auto pb-0 pt-0">
                        <a class="nav-link pt-0 pb-0" href="{{route('login.steam')}}">
                            <img src="{{ asset('img/steam_login.png') }}" alt="Steam Login"
                                 class="img-fluid">
                        </a>
                    </li>
                @endif
            </ul>
            @if(isset($configs['discord_invite_url']))
                <a href="{{ $configs['discord_invite_url'] }}" class="btn btn-discord ml-auto mt-2">
                    <i class="fab fa-discord"></i>
                    @lang('cosmo.core.theme_specific.join_discord')
                </a>
            @endif
        </div>
    </div>
</nav>