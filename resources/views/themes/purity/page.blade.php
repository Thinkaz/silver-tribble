@extends('themes.purity.layout')

@section('title', $page->title)

@section('page_titles')
    <h3 class="subtitle">{{ $page->title }}</h3>
@endsection

@section('content')
    <div class="container">
        {{ $page->content }}
    </div>
@endsection