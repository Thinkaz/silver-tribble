@extends('themes.havart.layout')

@section('title', $poll->title)

@section('header_title')
    <h1 class="title">{{ $poll->title }}</h1>
@endsection

@section('misc_content')
    <div class="text-center">
        <x-base.button>
            <x-slot name="link">{{ route('forums.polls.index') }}</x-slot>
            <x-slot name="style">btn-accent</x-slot>
            <x-slot name="icon">fad fa-square-poll-vertical</x-slot>
            Back to Polls
        </x-base.button>
    </div>
@endsection

@section('content')
    @include('themes.havart.includes.header')

    <div class="container polls" id="forums">
        <div class="row justify-content-center">
            <div class="col-md-8">
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
                    @if ((auth()->check() && $hasAnswered) or (auth()->check() && $poll->closed))
                        <div class="card-body">
                            <p class="text-white text-center font-weight-bold">{{ $poll->description }}</p>
                            <hr style="background-color: #ccc; width: auto; height: 1px; border-radius: 20px; margin-bottom: 1.3rem;">
                            @if (count($poll->userAnswers) > 0)
                            <div class="answers">
                                @foreach($poll->answers as $id => $answer)
                                    @php
                                        $getVotes = $poll->userAnswers;
                                        $finalCounting = [];

                                        for ($i = 0; $i <= count($getVotes); $i++) {
                                            $amount = 0;
                                            foreach ($getVotes as $indVote) {
                                                if ($indVote->answer == $i) {
                                                    $amount++;
                                                }
                                            }
                                            $finalCounting[$i] = $amount;
                                        }
                                    @endphp
                                    <div class="row justify-content-between progressSort">
                                        <div class="col-sm-2">
                                            <div class="text-white">{{ $answer }}</div>
                                        </div>

                                        <div class="col-sm-8">
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $finalCounting[$id] * 100 / count($poll->userAnswers) }}%; background-color: var(--Accent_Color);"
                                                 aria-valuenow="${value}" aria-valuemin="0" aria-valuemax="{{ count($poll->userAnswers) }}"></div>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="text-white">{{ $finalCounting[$id] }} Vote(s)</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @else
                                <div class="text-center mt-0">
                                    <span class="text-danger font-weight-bold">Can't display results as no one voted!</span>
                                </div>
                            @endif
                    @elseif (auth()->check() && !$hasAnswered)
                        <form action="{{route('forums.polls.store', $poll->id)}}" method="POST">
                            @csrf
                            <div class="card-body mb-0 pb-1">
                                <ul class="polls-group list-unstyled">
                                    <p class="text-white text-center font-weight-bold">{{ $poll->description }}</p>
                                    <hr style="background-color: #ccc; width: auto; height: 1px; border-radius: 20px">
                                    @foreach($poll->answers as $id => $answer)
                                        <li class="group">
                                            <div class="custom-control custom-radio mb-1">
                                                <input type="radio" class="custom-control-input"
                                                       id="answer-{{$id}}" name="answer" value="{{$id}}">
                                                <label class="custom-control-label text-white"
                                                       for="answer-{{$id}}"> {{ $answer }}</label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-footer text-center">
                                <x-base.button>
                                    <x-slot name="type">submit</x-slot>
                                    <x-slot name="style">{{ ($poll->closed or $poll->userHasAnswered(auth()->user())) ? 'btn-accent' : 'btn-success' }} w-100 h-100</x-slot>
                                    <x-slot name="icon">{{ $poll->closed ? 'fad fa-chart-pie' : 'fad fa-check-to-slot' }}</x-slot>
                                    @if ($poll->closed)
                                        @lang('cosmo.forums.polls_results')
                                    @else
                                        @lang('cosmo.forums.vote')
                                    @endif
                                </x-base.button>
                            </div>
                        </form>
                    @else
                        <a class="btn btn-danger w-100 h-100" href="{{route('login.steam')}}">
                            <i class="fad fa-circle-xmark"></i> Log in to vote or see the results
                        </a>
                    @endif
                    </div>
                </article>
            </div>
        </div>
    </div>
@endsection
