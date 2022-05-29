@extends('themes.havart.users.show._layout')

@section('profile-content')
    <div class="row justify-content-center">
        @if ($user->achievements->count() > 0)
            @foreach($user->achievements as $achievement)
                <div class="col-md-5 my-2">
                    <div class="card border-0 h-100 achievement">
                        <div class="card-body">
                            <div class="info">
                                <img src="{{ asset($achievement->icon) }}"
                                     style="height: 2.5rem; width: auto;" class="img-fluid" alt="form-post">
                                <span class="ml-2">{{$achievement->name}}</span>
                                <p class="text-muted mb-0 pb-0 pt-2">@lang('cosmo.users.achievements.achievement_unlocked')
                                    <color style="color: rgba(255,255,255,.75); font-weight: bold">
                                        {{$achievement->created_at->diffForHumans()}}</color>
                                    . {{$achievement->description}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            @if ($user == auth()->user())
                <div class="col-md-8 my-2">
                    <div class="card border-0 h-100 achievement">
                        <div class="card-body">
                            <div class="info">
                                <img src="{{ asset('img/achievements/more.png') }}"
                                     style="height: 2.5rem; width: auto;" class="img-fluid" alt="more-continue">
                                <span class="ml-2">@lang('cosmo.users.achievements.unlock_more')</span>
                                <p class="text-muted mb-0 pb-0 pt-2">@lang('cosmo.users.achievements.default')</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <div class="card border-top-accent shadow mb-3">
                        <div class="card-body">
                            <div class="d-inline">
                                <i class="fad fa-circle-exclamation fa-2x" style="color: var(--Accent_Color); font-size: 1.455rem"></i>
                            </div>
                            <div class="d-inline-block ml-2">
                                <h5>@lang('cosmo.users.no_achievement', ['name' => $user->username])</h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection