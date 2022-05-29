@extends('themes.lara.users.show._layout')

@section('profile-content')
    <div id="storeStats">
        <div class="row justify-content-center">
            <div class="col-md-3 my-2">
                <div class="card shadow">
                    <div class="card-header pb-0">
                        <h3 class="card-title">Total spent</h3>
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
                <div class="card shadow">
                    <div class="card-header pb-0">
                        <h3 class="card-title">Spent this month</h3>
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
                <div class="card shadow">
                    <div class="card-header pb-0">
                        <h3 class="card-title">Spent this week</h3>
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
                <div class="card shadow">
                    <div class="card-header pb-0">
                        <h3 class="card-title">Yearly Expenditure</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="yearly-chart" width="1000" height="500"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6 my-2">
                <div class="card shadow">
                    <div class="card-header pb-0">
                        <h3 class="card-title">Monthly Expenditure</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="monthly-chart" width="1000" height="500"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-5 shadow">
            <div class="card-header pb-0">
                <h3 class="card-title">Purchased packages</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-2 text-white" id="packagesData">
                        <thead>
                        <tr>
                            <th scope="col">Receiver</th>
                            <th scope="col">Buyer</th>
                            <th scope="col">Package</th>
                            <th scope="col">Price</th>
                            <th scope="col">Status</th>
                            <th scope="col">Permanent</th>
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
                                <td>{{ $order->status }}</td>
                                <td>{{ $order->package->permanent ? 'Yes' : 'No' }}</td>
                                <td>{{ $order->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{ $orders->links('themes.lara.pagination') }}
        </div>
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