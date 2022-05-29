@extends('themes.havart.layout')

@section('title')
    @lang('cosmo.forums.threads.create_thread')
@endsection

@section('header_title')
    <h1 class="title text-truncate">{{ $board->name }}</h1>
    <h3 class="subtitle">@lang('cosmo.forums.threads.create_thread')</h3>
@endsection

@section('crumbs')
    <li class="breadcrumb-item"><a href="{{ route('forums.index') }}">@lang('cosmo.core.home')</a></li>
    <li class="breadcrumb-item"><a href="{{ route('forums.boards.show', $board->id) }}">{{ $board->name }}</a></li>
    <li class="breadcrumb-item"><a class="text-truncate" href="{{ route('forums.threads.create', $board->id) }}">@lang('cosmo.actions.create')</a></li>
@endsection

@section('content')
    @include('themes.havart.includes.header')

    <div class="container mt-3" id="threads">
        @include('themes.havart.forums.breadcrumb')
        <form action="{{route('forums.threads.store', $board->id)}}" method="post">
            @csrf

            <div class="form-group">
                <label for="thread-title" class="thread-title">@lang('cosmo.forums.threads.thread_title')</label>
                <input type="text" name="title" class="form-control" id="thread-title" placeholder="Thread title..." value="{{old('title')}}">
            </div>

            <label for="thread-content" class="thread-title">@lang('cosmo.forums.threads.thread_content')</label>
            <textarea name="content" id="thread-content" aria-label="Content zone">{!! old('content') !!}</textarea>

            <x-base.button>
                <x-slot name="type">submit</x-slot>
                <x-slot name="style">btn-accent mt-3</x-slot>
                <x-slot name="icon">fad fa-edit</x-slot>
                @lang('cosmo.actions.create')
            </x-base.button>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/tinymce.js') }}"></script>
@endpush