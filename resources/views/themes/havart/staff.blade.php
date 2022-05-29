@extends('themes.havart.layout')

@section('title')
    @lang('cosmo.core.staff')
@endsection

@section('header_title')
    <h1 class="title">{{ $configs['staff_title'] }}</h1>
    <h3 class="subtitle">{{ $configs['staff_description'] }}</h3>
@endsection

@section('content')
    @include('themes.havart.includes.header')
    <div id="staff-cont">
        <img src="{{ asset('themes/havart/img/svgs/f_circle.svg') }}" alt="" class="svg_1">
        <div class="container my-5 leadership">
            <img src="{{ asset('themes/havart/img/svgs/elipsis8x4.svg') }}" alt="" class="svg_2">
            <div class="row justify-content-center">
                @foreach($users as $user)
                    <div class="col-md-3 my-3">
                        <x-havart.card :data="$user">
                            <x-slot name="type">user</x-slot>
                        </x-havart.card>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection