@extends('themes.havart.layout')

@section('title')
    {{$board->name}}
@endsection


@section('header_title')
    <h1 class="title">{{ $board->name }}</h1>
    <h3 class="subtitle">{{ $board->name }}'s @lang('cosmo.core.threads')</h3>
@endsection

@can('create', ['App\Models\Forums\Thread', $board])
@section('misc_content')
    <div class="text-center">
        <x-base.button>
            <x-slot name="type">link</x-slot>
            <x-slot name="link">{{ route('forums.threads.create', $board->id) }}</x-slot>
            <x-slot name="icon">fad fa-pen-to-square</x-slot>
            @lang('cosmo.forums.threads.create_thread')
        </x-base.button>
    </div>
@endsection
@endcan


@section('crumbs')
    <li class="breadcrumb-item"><a href="{{ route('forums.index') }}">@lang('cosmo.core.home')</a></li>

    @foreach($board->breadcrumbs as $breadcrumb)
        <li class="breadcrumb-item">
            <a href="{{ route('forums.boards.show', $breadcrumb->id) }}">
                {{ $breadcrumb->name }}
            </a>
        </li>
    @endforeach
@endsection

@section('content')
    @include('themes.havart.includes.header')

    <div class="container mt-4" id="forums">
        @include('themes.havart.forums.breadcrumb')

        @if ($board->subBoards->count() > 0)
            <div class="card mb-4" id="category">
                <div class="card-header">
                    <div>
                        <h5 class="card-title mb-0 pb-0">@lang('cosmo.forums.sub_board')</h5>
                    </div>
                </div>
                <div class="p-2 mb-0">
                    <x-havart.category :main="$board"/>
                </div>
            </div>
        @endif

        <div class="card mb-4" id="category">
            <div class="card-header">
                <div>
                    <h5 class="card-title mb-0 pb-0">{{ $board->name }}</h5>
                    <p class="card-desc mb-0 pb-0 text-truncate d-block">{{ $board->description }}</p>
                </div>
            </div>
            <div class="p-2 mb-0" id="threads">
                @if ($threads->count() > 0)
                    @foreach($threads as $thread)
                        <x-havart.thread :thread="$thread"/>
                    @endforeach
                @else
                    <span class="text-danger pl-4">
                        @lang('cosmo.forums.no_thread')
                    </span>
                @endif
            </div>
        </div>

        {{ $threads->links('themes.havart.pagination') }}
    </div>
@endsection
