<x-manage>
    <form action="{{ route('manage.general.pages.store') }}" method="post">
        @csrf

        <div class="page-title row justify-content-between">
            <h1 class="col-auto font-weight-bold text-lg">Pages <smalL>Creating Page</smalL></h1>

            <button type="submit" class="col-auto btn btn-success btn-icon-split btn-sm mr-3">
                <span class="icon text-white-50">
                  <i class="fad fa-check"></i>
                </span>
                <span class="text">Create Page</span>
            </button>
        </div>

        <div class="container mt-5">
            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" class="form-control" id="slug" name="slug">
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" id="content">
                    {{ old('content') }}
                </textarea>
            </div>
        </div>
    </form>

    <x-slot name="scripts">
        <script src="{{ asset('js/tinymce.js') }}"></script>
    </x-slot>
</x-manage>