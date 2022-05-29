@extends('themes.havart.layout')

@section('title')
    {{$server->name}}
@endsection

@section('header_title')
    <h1 class="title">{{$server->name}}</h1>
    <h3 class="subtitle">@lang('cosmo.store.browse_packages', ['server' => $server->name])</h3>
@endsection

@section('content')
    @include('themes.havart.includes.header')

    <div class="container my-5" id="store">

        <div class="packages">
            <div class="categories card border-0 bg-transparent">
                <ul class="nav nav-tabs mb-4 border-0" id="myTab" role="tablist">
                    <a class="btn btn-accent" href="?category=all">All Packages</a>
                    @foreach($categories as $cat)
                        <a class="btn btn-accent ml-2" href="?category={{ strtolower($cat)}}">{{$cat}}</a>
                    @endforeach
                </ul>

                <div class="row justify-content-center">
                    @foreach($packages as $package)
                        <form class="col-lg-4 col-md-6 col-sm-12 my-2 {{$package->category}}"
                              action="{{route('store.checkout.show', $package->id)}}">
                            <div class="card package h-100 border-0">
                                <div class="card-header">
                                    <div class="d-flex mt-2 mb-0">
                                        <div class="d-inline-flex">
                                            <h3 class="text-truncate">{{$package->name}}</h3>
                                        </div>
                                        <div class="d-inline-flex ml-auto mt-1">
                                            @if(!$package->custom_price)
                                                @if($package->sale)
                                                    <span class="price old my-auto mr-2 ml-n3"><del>{{$currency . $package->price}}</del></span>
                                                    <span class="price new my-auto">{{$currency . $package->finalPrice}}</span>
                                                @else
                                                    <span class="price">{{$currency . $package->price}}</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if ($package->image)
                                    <div class="card-image"
                                         style="background: linear-gradient(180deg, rgba(28, 28, 33, 0.70) 34%,
                                                 rgba(28, 28, 33, 0.75) 50%, rgba(28, 28, 33, 1) 100%),
                                                 url('{{ $package->image }}') no-repeat center center; height: 200px !important;"></div>
                                @endif

                                <div class="card-body pb-0">
                                    {{ $package->description }}
                                </div>
                                <div class="card-footer">
                                    @if($package->custom_price)
                                        <label for="custom-price"></label>
                                        <input type="number" min="0" class="form-control" name="custom-price"
                                               id="custom-price" placeholder="5.00">

                                    @endif
                                    <div class="purchase">
                                        <div class="row">
                                            <div class="col-md-6">
                                                @if ($package->permanent)
                                                    <span class="badge pl-4">@lang('cosmo.store.permanent')</span>
                                                @else
                                                    <span class="badge pl-4 non-permanent">@lang('cosmo.store.non-permanent')</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                @if(auth()->check())
                                                    <button class="btn btn-checkout pr-4">
                                                        @lang('cosmo.store.btn_checkout')
                                                    </button>
                                                @else
                                                    <a class="btn loggedOut" href="{{route('login.steam')}}">
                                                        @lang('cosmo.store.login_to-checkout')
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection