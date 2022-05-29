@extends('themes.dxrk.layout')

@section('title', $page->title)

@section('header')
    <h4 class="section-subheader">{{ $page->title }}</h4>
@endsection

@section('content')
    @include('themes.dxrk.includes.hero')

    <div class="container mt-4">
        {{ $page->content }}
    </div>
@endsection