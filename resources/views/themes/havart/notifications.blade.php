@extends('themes.havart.layout')

@section('title')
    @lang('cosmo.notifications.notifications')
@endsection

@section('header_title')
    <h1 class="title">{{ $configs['notifications_title'] }}</h1>
    <h3 class="subtitle">{{ $configs['notifications_description'] }}</h3>
@endsection

@section('content')
    @include('themes.havart.includes.header')

    <div class="container mb-5" id="notifications">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">@lang('cosmo.notifications.notifications')</h4>
                    </div>
                    <div class="forms">
                        <form action="{{route('notifications.markRead')}}" method="post" class="read">
                            @csrf
                            <button type="submit" class="btn btn-accent"><i class="fad fa-circle-check"></i> @lang('cosmo.notifications.mark_all_as_read')</button>
                        </form>

                        <form action="{{route('notifications.deleteAll')}}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-danger"><i class="fad fa-circle-xmark"></i> @lang('cosmo.notifications.delete_all')</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="notification_list">
                    @include('includes.notifications')

                    {{ $notifications->links() }}
                </ul>
            </div>
        </div>
    </div>
@endsection