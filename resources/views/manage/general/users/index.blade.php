<x-manage>
    <div class="page-title">
        <h1 class="font-weight-bold text-lg">Users <small>Browse Users</small></h1>
    </div>

    <div class="row justify-content-center mb-2">
        <div class="col-md-6">
            <form action="{{route('manage.general.users.index')}}" method="GET">
                @csrf

                <div class="input-group">
                    <input type="text" class="form-control bg-white border-0 small" placeholder="Search user"
                           name="search" value="{{ request()->get('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fad fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4 mt-3">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Users</h6>
        </div>
        <div class="card-body">
            @if(!$users->isEmpty())
                <div class="table-responsive">
                    <table class="table mb-2">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Steam Name</th>
                            <th scope="col">SteamID</th>
                            <th scope="col">Rank</th>
                            <th scope="col">Created</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <th scope="row">{{ $user->id }}</th>
                                <th scope="row">{{ $user->username }}</th>
                                <th scope="row">{{ $user->steamid }}</th>
                                <th scope="row">{{ $user->displayRole ? $user->displayRole->display_name : 'None' }}</th>
                                <th scope="row">{{ $user->created_at->diffForHumans() }}</th>
                                <th scope="row">
                                    @can('manage', $user)
                                        <a href="{{ route('manage.general.users.edit', $user->steamid) }}">
                                            <button class="btn btn-sm btn-primary" data-tippy-content="Edit User">
                                                <i class="fad fa-pencil"></i>
                                            </button>
                                        </a>
                                    @endcan
                                </th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $users->links() }}
            @else
                <div class="text-center mt-5">
                    <h1>No users found</h1>
                </div>
            @endif
        </div>
    </div>
</x-manage>

