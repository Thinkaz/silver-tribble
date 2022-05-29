@extends('themes.havart.layout')

@section('title')
    @lang('cosmo.core.users')
@endsection

@section('header_title')
    <h1 class="title">{{ $configs['users_title'] }}</h1>
    <h3 class="subtitle">{{ $configs['users_description'] }}</h3>
@endsection

@section('content')
    @include('themes.havart.includes.header')

    <div id="users-cont">
        <img src="{{ asset('themes/havart/img/svgs/f_circle.svg') }}" alt="" class="svg_1">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="{{route('users.search')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="search" placeholder="Search Users..." class="form-control" value="{{$search}}" aria-label="Search a user">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="container my-4" id="users">
            @if(!$users->isEmpty())
                <div class="row">
                    @foreach($users as $user)
                        <div class="col-md-3 my-3">
                            <x-havart.card :data="$user">
                                <x-slot name="type">user</x-slot>
                            </x-havart.card>
                        </div>
                    @endforeach
                </div>

                {{$users->links('themes.havart.pagination')}}
            @else
                <h2>@lang('cosmo.users.no_users-found')</h2>
            @endif
        </div>
        <img src="{{ asset('themes/havart/img/svgs/d-circle.svg') }}" alt="" class="svg_2">

    </div>
@endsection
