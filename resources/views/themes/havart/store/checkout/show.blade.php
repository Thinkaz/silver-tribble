@extends('themes.havart.layout')

@section('title')
    @lang('cosmo.core.checkout')
@endsection

@section('header_title')
    <h1 class="title">@lang('cosmo.store.complete_purchase')</h1>
    <h3 class="subtitle">@lang('cosmo.store.checking_out-package', ['package' => $package->name])</h3>
@endsection

@section('content')
    @include('themes.havart.includes.header')

    <div class="container my-5" id="checkout">
        <form method="post" id="checkout-form">
            @csrf

            <div class="row justify-content-between mb-4" style="position: relative; z-index: 99">
                <div class="col-md-3 couponCard">
                    @if (!$package->custom_price)
                        <div class="card h-100 border-bottom-accent shadow action">
                            <input type="text" placeholder="Enter Coupon Code" name="coupon" aria-label="Coupon code"/>
                            <h5 class="mb-0 mt-2 upper-title_small">@lang('cosmo.store.coupon-code')</h5>
                        </div>
                    @endif
                </div>
                <div class="col-md-3">
                    <div class="card h-100 border-bottom-accent shadow action">
                        <input type="text" placeholder="Enter Steam ID" name="gift" aria-label="Receiver"/>
                        <h5 class="mb-0 mt-2 upper-title_small">@lang('cosmo.store.gift-purchase')</h5>
                    </div>
                </div>
            </div>

            @if($package->custom_price)
                <input type="hidden" name="custom_price" value="{{request()->get('custom-price', 0)}}"/>
            @endif

            <div class="card h-100 shadow package_item">
                <div class="card-body mb-0">
                    <div class="card h-100" type="button" data-toggle="modal" data-target="#detailsModal">
                        @if ($package->image)
                            <div class="card-header border-bottom-0"
                                 style="background: linear-gradient(180deg, rgba(28, 28, 33, 0.80) 10%,
                                     rgba(28, 28, 33, 0.90) 85%, rgba(28, 28, 33, 1) 100%),
                                     url('{{ $package->image }}') no-repeat center center; height: 150px !important">
                                <h4 class="text-white text-center font-weight-bold mb-0" style="margin-top: 2rem !important">{{ $package->name }}</h4>
                                <h6 class="text-white text-center text-uppercase font-weight-bold">Click to view content</h6>
                            </div>
                        @else
                            <div class="card-header non-image">
                                <h4 class="text-white text-center mb-0 mt-0">{{ $package->name }}</h4>
                                <h6 class="text-white text-center text-uppercase">Click to view content</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card h-100 mt-4 border-bottom-accent shadow finalize">
                <div class="card-header border-0">
                    <h4 class="card-title font-weight-bold text-white text-center mb-0 pb-0">@lang('cosmo.store.finalize_purchase')</h4>
                    <div class="card_price mb-0 pb-0">
                        <h5 class="text-center text-white font-weight-light">
                            @lang('cosmo.store.you_pay'):

                            @if ($package->custom_price)
                                {{ $currency . (float)request()->get('custom-price') }}
                            @else
                                {{ $currency . $package->finalPrice }}
                            @endif
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-md-9 my-auto">
                            <div class="custom-control custom-checkbox tos">
                                <input type="checkbox" class="custom-control-input" id="tosAccept" name="tos">
                                <label class="custom-control-label text-muted" for="tosAccept">@lang('cosmo.store.tos_agree')</label>
                            </div>
                        </div>
                        <div class="col-md-3 ml-auto text-right button">
                            @php
                                $gtwExist = false;
                            @endphp
                            @foreach ($gateways as $name => $gateway)
                                @if ($gateway::isEnabled())
                                    @php
                                        $gtwExist = true;
                                    @endphp
                                    <button class="btn btn-accent mr-2" onclick="setGateway('{{ $name }}')">
                                        @lang('cosmo.store.checkout.pay_with') <i class="{{ $gateway::$icon }}"></i>
                                    </button>
                                @endif
                            @endforeach
                            @if (!$gtwExist)
                                <span class="text-danger">@lang('cosmo.store.checkout.no_gateway')</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal w-100" id="detailsModal" tabindex="-1" aria-labelledby="ModalContentPackage" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $package->name }}</h5>
                </div>
                <div class="modal-body">
                    {!! $package->description !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-accent" data-dismiss="modal"><i class="fad fa-circle-check"></i> Got it</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const gatewayRoutes = @json($gateway_urls);
        const form = document.getElementById('checkout-form');

        function setGateway(name) {
            if (typeof gatewayRoutes[name] === 'undefined') return;

            form.action = gatewayRoutes[name];
        }
    </script>
@endpush