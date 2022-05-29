@extends('themes.havart.layout')

@section('title', $page->title)

@section('header_title')
    <h1 class="title">{{ $page->title }}</h1>
@endsection

@section('content')
    @include('themes.havart.includes.header')

    <div class="container">
        {{ $page->content }}
    </div>
@endsection