@extends('themes.purity.layout')

@section('title')
    @lang('cosmo.core.staff')
@endsection

@section('page_titles')
    <h3 class="subtitle">{{$configs['staff_description']}}</h3>
    <h1 class="title">{{$configs['staff_title']}}</h1>
@endsection

@section('content')
    <div class="container" id="staff">
        <div class="row justify-content-center">
        @foreach($users as $user)
            <div class="col-md-3 my-3">
                <a class="card staff h-100 shadow border-0" href="{{route('users.show', $user->steamid)}}">
                    <div class="card-body d-flex">
                        <img src="{{ $user->avatar }}" alt="{{ $user->username }}'s avatar" class="img-fluid d-inline rounded">
                        <div class="d-inline my-auto text-truncate">
                            <div class="username">{{ $user->username }}</div>

                            @if ($user->displayRole)
                                <div class="role" style="color: {{ $user->displayRole->color }}">
                                    {{ $user->displayRole->display_name }}
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
        </div>
    </div>
@endsection