@extends('themes.havart.users.show._layout')

@section('profile-content')
    <div class="row justify-content-center features section ">
        @if (!is_null($user->profile->bio) && strlen($user->profile->bio) > 0)
            <div class="profile-card col-xl-3 col-lg-6 col-md-5 col-sm-12">
                <x-havart.card>
                    <x-slot name="type">feature</x-slot>
                    <x-slot name="icon">fa-book-user</x-slot>
                    <x-slot name="title">{{ $user->username }} biography</x-slot>
                    {!! $user->profile->bio !!}
                </x-havart.card>
            </div>
        @endif
        @if (!is_null($user->profile->signature) && strlen($user->profile->signature) > 0)
            <div class="profile-card col-xl-3 col-lg-6 col-md-5 col-sm-12">
                <x-havart.card>
                    <x-slot name="type">feature</x-slot>
                    <x-slot name="icon">fa-quote-left</x-slot>
                    @if ($user == auth()->user())
                        <x-slot name="title">Your signature</x-slot>
                    @else
                        <x-slot name="title">{{ $user->username }} signature</x-slot>
                    @endif
                    {!! $user->profile->signature !!}
                </x-havart.card>
            </div>
        @endif
        <div class="profile-card col-xl-3 col-lg-6 col-md-5 col-sm-12">
            <x-havart.card>
                <x-slot name="type">feature</x-slot>
                <x-slot name="icon">fa-clock</x-slot>
                <x-slot name="title">Join date</x-slot>
                {{ $user->username }} joined {{ $user->created_at->diffForHumans() }}
            </x-havart.card>
        </div>
    </div>
@endsection
