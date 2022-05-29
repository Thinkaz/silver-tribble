<x-manage>
    <div class="page-title">
        <h1 class="font-weight-bold text-lg d-inline-flex">
            Pages
        </h1>
    </div>

    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row justify-content-between mx-1">
                    <h6 class="m-0 font-weight-bold text-primary my-auto">
                        Pages
                    </h6>
                    <form action="{{ route('manage.general.pages.create') }}">
                        <button class="btn btn-success btn-sm">
                            Create Page
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-2">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Pages</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($pages as $page)
                            <tr class="text-truncate">
                                <th scope="row">{{ $page->id }}</th>
                                <th scope="row">{{ $page->title }}</th>
                                <th scope="row">{{ $page->slug }}</th>
                                <th scope="row">{{ $page->created_at->diffForHumans() }}</th>

                                <th scope="row">
                                    <form class="d-inline" action="{{ route('manage.general.pages.edit', $page->id) }}">
                                        <button type="submit" class="btn btn-sm btn-warning"><i class="fad fa-pencil"></i></button>
                                    </form>

                                    <form class="d-inline" method="post"
                                          action="{{ route('manage.general.pages.destroy', $page->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fad fa-trash"></i></button>
                                    </form>
                                </th>
                            </tr>
                        @empty
                            <tr>
                                <th scope="row">0</th>
                                <th scope="row">Create a new page</th>
                                <th scope="row">None</th>
                                <th scope="row">00-00-00</th>
                                <th scope="row">##</th>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{ $pages->links() }}
                </div>
            </div>
        </div>
    </div>
</x-manage>