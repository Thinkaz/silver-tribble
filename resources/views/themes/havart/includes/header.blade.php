<div class="header_full mt-0">

    @include('themes.havart.includes.navbar')

    <div class="py-5" id="hero overflow-hidden">
        <div class="container py-5" id="hero-inner">
            <div class="titles">
                @yield('header_title')
            </div>
            <div id="misc_content">
                @yield('misc_content')
            </div>
        </div>
    </div>
</div>