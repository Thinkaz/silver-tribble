@extends('themes.havart.layout')

@section('title')
    @lang('cosmo.forums.posts.editing_post')
@endsection

@section('header_title')
    <h1 class="title text-truncate">Editing Post</h1>
@endsection

@section('content')
    @include('themes.havart.includes.header')

    <div class="container mt-3">
        <form action="{{route('forums.posts.update', $post->id)}}" method="post">
            @csrf
            @method('PATCH')

            <textarea name="content" aria-label="Content zone">{!! $post->content !!}</textarea>

            <x-base.button>
                <x-slot name="type">submit</x-slot>
                <x-slot name="style">btn-accent mt-3</x-slot>
                <x-slot name="icon">fad fa-edit</x-slot>
                @lang('cosmo.actions.update')
            </x-base.button>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/tinymce.js') }}"></script>
@endpush