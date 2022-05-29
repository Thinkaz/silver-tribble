@extends('themes.havart.layout')

@section('title', 'Poll Listing')

@section('header_title')
    <h1 class="title">@lang('cosmo.forums.polls_title')</h1>
    <h3 class="subtitle">@lang('cosmo.forums.polls_desc')</h3>
@endsection

@section('misc_content')
    <div class="text-center">
        <x-base.button>
            <x-slot name="link">{{ route('forums.index') }}</x-slot>
            <x-slot name="style">btn-accent</x-slot>
            <x-slot name="icon">fad fa-comment-lines</x-slot>
            @lang('cosmo.forums.back_forums')
        </x-base.button>
    </div>
@endsection

@section('content')
    @include('themes.havart.includes.header')

    <div class="container polls" id="forums">
        @if(!is_null($polls) && $polls->count() > 0 && auth()->check())
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="{{route('forums.polls.index')}}" method="get">
                        @csrf
                        <div class="form-group text-center">
                            <input type="text" name="search" id="search" placeholder="Search a poll.." class="form-control" value="{{$search}}">
                        </div>
                    </form>
                </div>
            </div>

            <div class="row justify-content-center">
                @foreach($polls as $poll)
                    <div class="col-md-4 my-4">
                        <article class="card h-100 border-0" id="category">
                            <div class="card-header d-flex">
                                <h3 class="d-inline-flex card-title my-auto mr-auto">{{ $poll->title }}</h3>
                                <h5 class="d-inline-flex my-auto">
                                    <span class="badge w-100 align-items-center p-2 gradient_bottom {{ $poll->closed ? 'badge-danger' : 'badge-success' }}">
                                        @if ($poll->closed)
                                            @lang('cosmo.forums.closed')
                                        @else
                                            @lang('cosmo.forums.open')
                                        @endif
                                    </span>
                                    <span class="badge badge-accent w-100 align-items-center p-2 ml-2 gradient_bottom">
                                        @if (count($poll->userAnswers) > 0)
                                            {{ count($poll->userAnswers).' Vote(s)' }}
                                        @else
                                            @lang('cosmo.forums.no_vote')
                                        @endif
                                    </span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="description">{{ $poll->description }}</p>
                            </div>
                            <div class="card-footer text-center">
                                <x-base.button>
                                    <x-slot name="link">{{ route('forums.polls.show', $poll->id) }}</x-slot>
                                    <x-slot name="style">{{ ($poll->closed or $poll->userHasAnswered(auth()->user())) ? 'btn-accent' : 'btn-success' }} w-100 h-100</x-slot>
                                    <x-slot name="icon">{{ ($poll->closed or $poll->userHasAnswered(auth()->user())) ? 'fad fa-chart-pie' : 'fad fa-check-to-slot' }}</x-slot>
                                    @if ($poll->closed or $poll->userHasAnswered(auth()->user()))
                                        @lang('cosmo.forums.polls_results')
                                    @else
                                        @lang('cosmo.forums.vote')
                                    @endif
                                </x-base.button>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @elseif(!auth()->check())
            <a class="btn btn-danger w-100 h-100" href="{{route('login.steam')}}">
                <i class="fad fa-circle-xmark"></i> Log in to see the ongoing polls
            </a>
        @else
            <h5 class="text-warning text-center">@lang('cosmo.forums.no_poll')</h5>
        @endif

        {{ $polls->links() }}
    </div>

@endsection