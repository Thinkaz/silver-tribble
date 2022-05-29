<x-manage>
    <div class="page-title d-flex justify-content-between">
        <h1 class="font-weight-bold text-lg">Users <small>Editing {{ $user->username }}</small></h1>

        <div>
            <button class="btn btn-success btn-sm btn-icon-split" data-toggle="modal" data-target="#assign-package">
                        <span class="icon text-white-50">
                          <i class="fad fa-gift"></i>
                        </span>
                <span class="text">Assign Package</span>
            </button>

            <button class="btn btn-danger btn-sm btn-icon-split" data-toggle="modal" data-target="#ban-user">
                        <span class="icon text-white-50">
                          <i class="fad fa-ban"></i>
                        </span>
                <span class="text">Ban User</span>
            </button>
        </div>
    </div>

    <div class="modal fade" id="assign-package" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Package</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('manage.general.users.assign', $user->steamid) }}" method="post">
                    @csrf

                    <div class="modal-body">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <label class="input-group-text"
                                       for="package">Package</label>
                            </div>
                            <select id="package" class="form-control" name="package">
                                @foreach($packages as $id => $name)
                                    <option value="{{ $id }}">{{ $name }} (ID: {{ $id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ban-user" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="{{ route('manage.general.bans.store', $user->steamid) }}" method="post">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ban User</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="ban-{{ $user->id }}-reason">Reason</label>
                            <input type="text" class="form-control" id="ban-{{ $user->id }}-reason"
                                   placeholder="Chargeback" name="reason">
                        </div>

                        <label class="mt-2">Platforms</label>
                        @foreach (['forums', 'store'] as $platform)
                            <div class="custom-control custom-checkbox mt-2">
                                <input type="checkbox" class="custom-control-input" name="platforms[]"
                                       id="ban-{{ $user->id }}-platform-{{ $platform }}" value="{{ $platform }}">
                                <label class="custom-control-label text-capitalize"
                                       for="ban-{{ $user->id }}-platform-{{ $platform }}">
                                    {{ $platform }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Ban User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form class="mt-5 container" action="{{ route('manage.general.users.update', $user->steamid) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}">
        </div>

        <div class="form-group">
            <label>Roles</label>

            @foreach($roles as $role)
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="roles-{{ $role->name }}" name="roles[]" value="{{ $role->id }}" {{ $user->hasRole($role->id) ? 'checked=""' : '' }}>
                    <label class="custom-control-label" for="roles-{{ $role->name }}">{{ $role->display_name }} ({{ $role->name }})</label>
                </div>
            @endforeach
        </div>

        <div class="form-group">
            <label for="avatar">Avatar</label>
            <input type="text" class="form-control" id="avatar" name="avatar" value="{{ $user->avatar }}">
        </div>

        <div class="form-group">
            <label for="bio">Biography</label>
            <textarea class="form-control" id="bio" name="bio" rows="3">
                {{ $user->profile->bio }}
            </textarea>
        </div>

        <div class="form-group">
            <label for="signature">Signature</label>
            <textarea class="form-control" id="signature" name="signature" rows="3">
                {{ $user->profile->signature }}
            </textarea>
        </div>

        <div class="form-group">
            <label for="background_img">Background Image</label>
            <input type="text" class="form-control" id="background_img" name="background_img" value="{{ $user->profile->background_img }}">
        </div>

        <button class="btn btn-primary" type="submit">
            Update User
        </button>
    </form>
</x-manage>
