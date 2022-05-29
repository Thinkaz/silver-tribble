@props(['user'])

<div class="mt-3">
    @foreach ($user->roles->whereNotNull('banner_image')->sortByDesc('precedence') as $role)
        <img style="max-width: 100%;" src="{{ $role->banner_image }}">
    @endforeach
</div>