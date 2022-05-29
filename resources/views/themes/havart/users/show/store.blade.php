@extends('themes.havart.users.show._layout')

@section('profile-content')
    <div id="storeStats">
        <div class="row justify-content-center">
            <div class="col-md-3 my-2">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3 class="card-title">@lang('cosmo.users.store.total')</h3>
                    </div>
                    <div class="card-body">
                        <h4><span>{{ format_price($totalSpent) }}</span></h4>
                        <span class="fa-stack fa-2x">
                          <i class="fas fa-square fa-stack-2x square"></i>
                          <i class="fad fa-dollar fa-stack-1x fa-inverse icon"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 my-2">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3 class="card-title">@lang('cosmo.users.store.monthly_spent')</h3>
                    </div>
                    <div class="card-body">
                        <h4><span>{{ format_price($monthlySpending) }}</span></h4>
                        <span class="fa-stack fa-2x">
                          <i class="fas fa-square fa-stack-2x square"></i>
                          <i class="fad fa-dollar fa-stack-1x fa-inverse icon"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 my-2">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3 class="card-title">@lang('cosmo.users.store.weekly_spent')</h3>
                    </div>
                    <div class="card-body">
                        <h4><span>{{ format_price($weeklySpending) }}</span></h4>
                        <span class="fa-stack fa-2x">
                          <i class="fas fa-square fa-stack-2x square"></i>
                          <i class="fad fa-dollar fa-stack-1x icon"></i>
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <div class="row justify-content-center my-5">
            <div class="col-md-6 my-2">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3 class="card-title">@lang('cosmo.users.store.yearly_exp')</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="yearly-chart" width="1000" height="500"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6 my-2">
                <div class="card">
                    <div class="card-header pb-0">
                        <h3 class="card-title">@lang('cosmo.users.store.monthly_exp')</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="monthly-chart" width="1000" height="500"></canvas>
                    </div>
                </div>
            </div>
        </div>

        @if ($orders->count() > 0)
            <div class="card mt-5">
                <div class="card-header pb-0">
                    <h3 class="card-title">@lang('cosmo.users.store.packages')</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-2 text-white" id="packagesData">
                            <thead>
                            <tr>
                                <th scope="col">@lang('cosmo.users.store.receiver')</th>
                                <th scope="col">@lang('cosmo.users.store.customer')</th>
                                <th scope="col">@lang('cosmo.users.store.package')</th>
                                <th scope="col">@lang('cosmo.users.store.price')</th>
                                <th scope="col">Status</th>
                                <th scope="col">@lang('cosmo.users.store.perm')</th>
                                <th scope="col">Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <th scope="row">{{ $order->receiver !== $user->steamid ? $order->receiver : 'Self' }}</th>
                                    <td>{{ $user->username}}</td>
                                    <td>{{ $order->package->name }}</td>
                                    <td>{{ format_price($order->price) }}</td>
                                    <td style="{{ $order->status == 'delivered' ? 'color: var(--success);' : '' }}">{{ $order->status }}</td>
                                    <td style="{{ $order->package->permanent ? 'color: var(--success);' : 'color: var(--danger);'}}">{{ $order->package->permanent ? 'Yes' : 'No' }}</td>
                                    <td><span data-tippy-content="{{ $order->created_at }}">{{ $order->created_at->diffForHumans() }}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $orders->links('themes.havart.pagination') }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script type="application/javascript" src="{{ asset('js/manage/dashboard.js') }}"></script>

    <script>
        const yearlyData = fillInBlanks(@json($yearlySpendingGraph), 12);
        const monthlyData = fillInBlanks(@json($monthlySpendingGraph), 31);

        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        const yearly = document.getElementById('yearly-chart').getContext('2d');
        window.createChart(yearly, months, Object.values(yearlyData), 'rgb(155, 89, 182)', 'rgba(155, 89, 182, 0.5)');

        const monthly = document.getElementById('monthly-chart').getContext('2d');
        window.createChart(monthly, Object.keys(monthlyData), Object.values(monthlyData), 'rgb(231, 76, 60)', 'rgba(231, 76, 60, 0.5)');
    </script>
@endpush