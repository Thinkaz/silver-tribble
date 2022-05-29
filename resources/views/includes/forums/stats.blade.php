<div class="col-12 col-md-3 forum_stats">
    <a href="{{route('forums.polls.index')}}"
       class="badge {{ $pollCount > 0 ? 'btn-accent' : 'btn-background' }} w-100 align-items-center p-3 mb-2 gradient_bottom position-relative" style="z-index: 10">
        @if ($pollCount > 0)
            <i class="fad fa-bell-on fa-shake fa-3x pt-2"></i>
        @endif
        <h6 class="text-truncate pt-2 {{ $pollCount > 0 ? 'text-uppercase' : '' }}">
            {{ $pollCount > 0 ? $pollCount.' unanswered poll(s)' : 'View active polls' }}
        </h6>
    </a>

    <x-havart.card>
        <x-slot name="type">statforum</x-slot>
        <x-slot name="id">search_threads</x-slot>
        <x-slot name="icon">fa-search</x-slot>
        <x-slot name="title">@lang('cosmo.forums.search')</x-slot>
        <x-slot name="description">@lang('cosmo.forums.search_desc')</x-slot>
    </x-havart.card>

    @if($recentPosts->count() > 0)
            <x-havart.card :data="$recentPosts">
                <x-slot name="type">statforum</x-slot>
                <x-slot name="id">latest_posts</x-slot>
                <x-slot name="icon">fa-comment-dots</x-slot>
                <x-slot name="title">@lang('cosmo.forums.latest_posts')</x-slot>
                <x-slot name="description">@lang('cosmo.forums.latest_posts-desc')</x-slot>
            </x-havart.card>
    @endif

    @if($recentThreads->count() > 0)
            <x-havart.card :data="$recentThreads">
                <x-slot name="type">statforum</x-slot>
                <x-slot name="id">latest_threads</x-slot>
                <x-slot name="icon">fa-comments</x-slot>
                <x-slot name="title">@lang('cosmo.forums.latest_threads')</x-slot>
                <x-slot name="description">@lang('cosmo.forums.latest_threads-desc')</x-slot>
            </x-havart.card>
    @endif

    @if($configs['discord_widget_enabled'])
        <iframe src="https://discordapp.com/widget?id={{$configs['discord_widget_id']}}&theme=dark"
                class="position-relative mt-4 discord-responsive" style="z-index: 999" width="330" height="400"
                allowtransparency="true" frameborder="0">
        </iframe>
    @endif
</div>