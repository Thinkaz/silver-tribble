<x-manage>
    <div x-data="{ creating: true }">
        <div class="page-title">
            <h1 class="font-weight-bold text-lg">Webhooks <small>{{ $webhooks->count() }} webhooks</small></h1>
        </div>

        <div class="card shadow">
            <div class="card-header py-3" @click="creating = !creating">
                <h6 class="m-0 font-weight-bold text-primary">Create Webhook</h6>
            </div>
            <div class="card-body" x-show="creating">
                <form action="{{ route('manage.general.webhooks.store') }}" method="post">
                    @csrf

                    <div class="d-flex mb-3">
                        <div class="w-100 pr-4">
                            <div class="w-100">
                                <label for="url">Webhook URL</label>
                                <input type="text" class="form-control" id="url" name="url">
                            </div>
                        </div>

                        <div class="mr-3 my-auto">
                            <div>
                                <label for="type">Webhook Type</label>

                                <select class="custom-select" id="type" name="type">
                                    @foreach ($webhookTypes as $type => $name)
                                        <option value="{{ $type }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="w-25">
                            <label for="triggers_on">Webhook Triggers</label>

                            @foreach ($triggers as $trigger => $name)
                                <div>
                                    <input type="checkbox" name="triggers_on[]" value="{{ $trigger }}"/>
                                    <label for="triggers_on[]">{{ $name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="w-100 mx-3">
                            <label for="description">Webhook Description</label>
                            <input type="text" class="form-control" id="description" name="description">
                        </div>

                        <div class="mt-auto flex-grow-1 justify-content-end" style="height: 38px">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-5">
        @foreach ($webhooks as $webhook)
            <div class="modal fade" id="edit-{{$webhook->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form action="{{ route('manage.general.webhooks.update', $webhook->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="modal-header">
                                <h5 class="modal-title">Editing Webhook: {{ $webhook->description ?? $webhook->id }}</h5>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <label for="url">Webhook URL</label>
                                    <input type="text" class="form-control" id="url" name="url" value="{{ $webhook->url }}">
                                </div>

                                <div class="mt-3">
                                    <label for="type">Webhook Type</label>
                                    <select class="custom-select" id="type" name="type">
                                        @foreach ($webhookTypes as $type => $name)
                                            <option value="{{ $type }}" {{ $webhook->type === $type ? 'selected=""' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-3">
                                    <label for="triggers_on">Webhook Triggers</label>

                                    @foreach ($triggers as $trigger => $name)
                                        <div>
                                            <input type="checkbox" name="triggers_on[]" value="{{ $trigger }}" {{ in_array($trigger, $webhook->triggers_on) ? 'checked=""' : '' }} />
                                            <label for="triggers_on[]">{{ $name }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3">
                                    <label for="description">Webhook Description</label>
                                    <input type="text" class="form-control" id="description" name="description" value="{{ $webhook->description }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="delete-{{$webhook->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Deleting Webhook: {{ $webhook->description ?? $webhook->id }}</h5>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this webhook?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                            <form action="{{route('manage.general.webhooks.destroy', $webhook->id)}}" method="post">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-2" x-data="{ showingSecret: false }">
                <div class="p-3 bg-white shadow rounded">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-primary">
                                {{ $webhook->description ?? $webhook->url }}
                            </p>

                            <p class="mb-0">
                                Triggers on:
                                <span class="text-secondary">
                            {{ collect($webhook->triggers_on)->map(fn ($val) => $triggers[$val])->join(', ') }}
                        </span>
                            </p>

                            <p class="mb-0">
                                Type: {{ $webhookTypes[$webhook->type] }}
                            </p>
                        </div>

                        <div class="my-auto mr-3">
                            <button class="btn btn-sm btn-circle btn-primary ml-3" data-toggle="modal" data-target="#edit-{{$webhook->id}}">
                                <i class="fad fa-pencil"></i>
                            </button>

                            <button class="btn btn-sm btn-circle btn-danger" data-toggle="modal" data-target="#delete-{{$webhook->id}}">
                                <i class="fad fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <x-slot name="meta">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    </x-slot>

    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    </x-slot>
</x-manage>