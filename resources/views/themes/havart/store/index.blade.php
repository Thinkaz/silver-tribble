@extends('themes.havart.layout')

@section('title')
    @lang('cosmo.core.store')
@endsection

@section('header_title')
    <h1 class="title">{{ $configs['store_title'] }}</h1>
    <h3 class="subtitle">{{ $configs['store_description'] }}</h3>
@endsection

@section('content')
    @include('themes.havart.includes.header')

    <div class="container my-5" id="store">
        @if($configs['monthly_goal_enabled'])
            <h3 class="text-center top_donations">@lang('cosmo.store.monthly_goal')</h3>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{$goalProgress}}%;" aria-valuenow="{{$goalProgress}}" aria-valuemin="0" aria-valuemax="100">
                    {{ ceil($goalProgress) }}%
                </div>
            </div>
        @endif

        <img src="{{ asset('themes/havart/img/svgs/elipsis8x4.svg') }}" class="svg-elipsis img-fluid" alt="">

        @if($configs['show_store_statistics'])
            @if ($top->count() > 0)
                <div class="row justify-content-center mb-4">
                    <div class="col-md-12">
                        <h3 class="text-center top_donations">@lang('cosmo.store.table.top_donations')</h3>
                        <table class="table mb-2 text-white">
                            <thead>
                            <tr>
                                <th scope="col">@lang('cosmo.store.table.name')</th>
                                <th scope="col">@lang('cosmo.store.table.amount')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($top->take(10) as $transaction)
                                <tr>
                                    <td>{{ $transaction->buyer->username }}</td>
                                    <td>{{$currency}}{{ $transaction->sum }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            @if ($recent->count() > 0)
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <h3 class="text-center top_donations">@lang('cosmo.store.table.recent_donations')</h3>
                        <table class="table table-stripped">
                            <thead class="thead">
                                <tr>
                                    <th scope="col">@lang('cosmo.store.table.name')</th>
                                    <th scope="col">@lang('cosmo.store.table.package')</th>
                                    <th scope="col">@lang('cosmo.store.table.amount')</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @foreach($recent as $transaction)
                                    <tr>
                                        <td>{{ $transaction->buyer->username }}</td>
                                        <td>{{ $transaction->package->name }}</td>
                                        <td>{{$currency}}{{ $transaction->price }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endif

        <img src="{{ asset('themes/havart/img/svgs/f_circle.svg') }}" class="svg-circle_full img-fluid" alt="">

        <div class="store-content">
            {!! $configs['store_header'] !!}
        </div>

        <div class="row justify-content-center servers">
            @foreach($servers as $server)
                <div class="col-md-4 my-2">
                    <a href="{{route('store.servers.show', $server->id)}}" class="card card-server server"
                         style="background-image: linear-gradient(180deg, rgba(28, 28, 33, 0.66) 75%, rgba(28, 28, 33, 1) 100%),
                                 url({{ $server->image  }}); background-size: cover; background-repeat: no-repeat;">
                        <h3 class="card-title text-truncate mt-auto mb-auto">{{$server->name}}</h3>
                    </a>
                </div>
            @endforeach
        </div>
        <img src="{{ asset('themes/havart/img/svgs/d-circle.svg') }}" alt="" class="svg-circle_dashed img-fluid">
    </div>
@endsection