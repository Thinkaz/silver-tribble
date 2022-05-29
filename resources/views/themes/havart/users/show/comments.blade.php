@extends('themes.havart.users.show._layout')

@section('profile-content')
    @if ($comments->count() > 0)
        @foreach($comments as $comment)
            @can('update', $comment)
                <div class="modal fade" id="edit-comment-{{ $comment->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{route('users.comments.update', ['user' => $user->steamid, 'comment' => $comment->id])}}" method="post">
                                @csrf
                                @method('PATCH')
                                <div class="modal-header">
                                    <h5 class="modal-title text-white">Edit this comment</h5>
                                </div>
                                <div class="modal-body">
                                    <textarea name="content" aria-label="Update comment">{{ $comment->content }}</textarea>
                                </div>
                                <div class="modal-footer">
                                    <x-base.button>
                                        <x-slot name="dismiss">modal</x-slot>
                                        <x-slot name="style">btn-secondary</x-slot>
                                        <x-slot name="icon">fad fa-circle-xmark</x-slot>
                                        @lang('cosmo.actions.close')
                                    </x-base.button>
                                    <x-base.button>
                                        <x-slot name="type">submit</x-slot>
                                        <x-slot name="style">btn-accent</x-slot>
                                        <x-slot name="icon">fad fa-edit</x-slot>
                                        @lang('cosmo.users.update_comment')
                                    </x-base.button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
            <div class="card shadow border-0 mb-1 mt-3 thread reps" id="post-{{$comment->id}}">
                <div class="card-body pt-3 pb-0">
                    <div class="row">
                        <div class="col-md-1">
                            <div class="content text-center">
                                <a href="{{ route('users.show', $comment->user->steamid) }}">
                                    <img src="{{$comment->user->avatar}}" class="rounded-circle" style="max-height: 80px;" alt="User Avatar">
                                </a>
                            </div>
                       </div>
                        <div class="col pl-0 comment-text">
                            <a href="{{ route('users.show', $comment->user->steamid) }}">
                                <h5 class="comment-username" style="color: {{ $comment->user->displayRole->color }}">
                                    {{ $comment->user->username == auth()->user()->username ? $comment->user->username . ' (You)' : $comment->user->username}}
                                </h5>
                            </a>
                            {!! $comment->content !!}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex">
                        <div class="d-inline-flex mr-auto">
                            <span class="text-muted ml-2 mr-2 mt-1" style="font-size: .87rem;"><i class="fad fa-clock pr-1"></i> @lang('cosmo.core.posted') {{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="d-inline-flex ml-auto">
                            @can('update', $comment)
                                <x-base.button>
                                    <x-slot name="modal">#edit-comment-{{ $comment->id }}</x-slot>
                                    <x-slot name="style">btn-sm btn-accent mr-2</x-slot>
                                    <x-slot name="icon">fad fa-edit</x-slot>
                                    @lang('cosmo.core.edit')
                                </x-base.button>
                            @endcan
                            @can('delete', $comment)
                                <form action="{{route('users.comments.destroy', ['user' => $user->steamid, 'comment' => $comment->id])}}"
                                      method="post">
                                    @csrf
                                    @method('DELETE')
                                    <x-base.button>
                                        <x-slot name="type">submit</x-slot>
                                        <x-slot name="style">btn-sm btn-danger</x-slot>
                                        <x-slot name="icon">fad fa-trash</x-slot>
                                        @lang('cosmo.actions.delete')
                                    </x-base.button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="row justify-content-center">
            <div class="text-center">
                <div class="card border-top-accent shadow mb-3">
                    <div class="card-body">
                        <div class="d-inline">
                            <i class="fad fa-circle-exclamation fa-2x" style="color: var(--Accent_Color); font-size: 1.455rem"></i>
                        </div>
                        <div class="d-inline-block ml-2">
                            <h5>@lang('cosmo.users.no_comment')</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @auth
        <form action="{{route('users.comments.store', $user->steamid)}}" method="post">
            @csrf
            <div class="card shadow thread mt-3">
                <div class="card-body">
                    <textarea name="content" aria-label="Comment zone"></textarea>
                    <x-base.button>
                        <x-slot name="type">submit</x-slot>
                        <x-slot name="style">btn-accent mt-3</x-slot>
                        <x-slot name="icon">fad fa-message-lines</x-slot>
                        @lang('cosmo.users.comment')
                    </x-base.button>
                </div>
            </div>
        </form>
    @endauth

    {{ $comments->links('themes.havart.pagination') }}
@endsection

@push('scripts')
    <script src="{{ asset('js/tinymce.js') }}"></script>
@endpush