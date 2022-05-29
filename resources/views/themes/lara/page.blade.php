@extends('themes.lara.layout')

@section('title', $page->title)

@section('header_title')
    <h3 class="title">{{ $page->title }}</h3>
@endsection

@section('content')
    @include('themes.lara.includes.header')

    <div class="container mt-4">
        {{ $page->content }}
    </div>
@endsection