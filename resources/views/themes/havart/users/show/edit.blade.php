@extends('themes.havart.users.show._layout')

@section('profile-content')
    <div  id="users_edit" role="tabpanel" aria-labelledby="pills-users_edit-tab">
        <div class="card">
            @if($configs['discord_sync_enabled'])
            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-auto d-flex d-inline-flex pt-2">
                        <h3 class="text-white">
                            @lang('cosmo.users.pills.edit')
                        </h3>
                    </div>
                    <div class="col-auto d-flex d-inline-flex" style="padding-top: 10px;">
                        <form action="{{route('login.discord')}}" method="get">
                            <x-base.button>
                                <x-slot name="type">submit</x-slot>
                                <x-slot name="style">btn btn-accent</x-slot>
                                <x-slot name="icon">fab fa-discord</x-slot>
                                @lang('cosmo.users.edit.sync_discord')
                            </x-base.button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            <div class="card-body">
                <form action="{{route('users.update', $user->steamid)}}" method="post" id="edit-form">
                    @csrf
                    @method('PATCH')
                    <div class="row justify-content-center mb-4">
                        <div class="col-lg-8">
                            <p class="mt-4 profile_edit-text">@lang('cosmo.users.edit.display_name'):</p>
                            <input type="text" class="form-control" name="username" id="username"
                                   value="{{$user->username}}" placeholder="Display Name" aria-label="Username">
                        </div>

                        <div class="col-lg-8">
                            <p class="mt-4 profile_edit-text">@lang('cosmo.users.edit.background_image')
                                :</p>
                            <input type="text" class="form-control" name="background_img"
                                   id="background-image" value="{{$user->profile->background_img}}"
                                   placeholder="Background Image" aria-label="Background image">
                        </div>

                        <div class="col-md-8">
                            <p class="mt-4 profile_edit-text">@lang('cosmo.users.edit.biography'):</p>
                            <textarea name="bio" aria-label="User bio">{!! $user->profile->bio !!}</textarea>
                        </div>

                        <div class="col-md-8">
                            <p class="mt-4 profile_edit-text">@lang('cosmo.users.edit.signature'):</p>
                            <textarea name="signature" aria-label="User signature"> {{$user->profile->signature}} </textarea>
                        </div>
                        <div class="col-md-8 mt-4 text-center">
                            <x-base.button>
                                <x-slot name="type">submit</x-slot>
                                <x-slot name="style">btn btn-success</x-slot>
                                <x-slot name="icon">fad fa-pen-to-square</x-slot>
                                @lang('cosmo.actions.update')
                            </x-base.button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/tinymce.js') }}"></script>
@endpush