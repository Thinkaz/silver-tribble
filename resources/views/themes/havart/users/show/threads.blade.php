@extends('themes.havart.users.show._layout')

@section('profile-content')
    <div class="row justify-content-center">
        @php
            $i = 0;
        @endphp
        @if ($threads->count() > 0)
            <div class="card mb-2">
                <div class="card-header pb-0">
                    <h3 class="card-title">@lang('cosmo.users.threads.posted_by', ['name' => $user->username])</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-2 text-white" id="packagesData">
                            <thead>
                            <tr>
                                <th scope="col">@lang('cosmo.core.board')</th>
                                <th scope="col">@lang('cosmo.core.title')</th>
                                <th scope="col">@lang('cosmo.forums.replies')</th>
                                <th scope="col">Date</th>
                                <th scope="col">@lang('cosmo.forums.threads.pinned')</th>
                                <th scope="col">@lang('cosmo.forums.threads.locked')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($threads as $thread)
                                <tr>
                                    <td><a href="{{ route('forums.boards.show', $thread->board->id) }}">{{ $thread->board->name }}</a></td>
                                    <td><a href="{{ route('forums.threads.show', $thread->id) }}">{{ $thread->title }}</a></td>
                                    <td>{{ $thread->posts_count }}</td>
                                    <td data-tippy-content="{{ $thread->created_at }}">{{ $thread->created_at->diffForHumans() }}</td>
                                    <td style="color: {{ $thread->stickied ? "var(--Accent_Color);" : "" }}">{{ $thread->stickied ? 'Yes' : 'No' }}</td>
                                    <td style="color: {{ $thread->locked ? "var(--danger);" : "" }}">{{ $thread->locked ? 'Yes' : 'No' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $threads->links('themes.havart.pagination') }}
            </div>
            <div class="col-md-3 my-auto">
                <div class="card" style="margin-bottom: 2rem !important">
                    <div class="card-header pb-0">
                        <h3 class="card-title">@lang('cosmo.users.threads.total_thread')</h3>
                    </div>
                    <div class="card-body">
                        <h4>
                            <span class="fa-stack fa-1x">
                                <i class="fad fa-square fa-stack-2x" style="color: var(--Accent_Background)"></i>
                                <i class="fad fa-messages fa-stack-1x" style="color: var(--Accent_Color)"></i>
                            </span> <span>@lang('cosmo.users.threads.thread', ['amount' => $threads->count()])</span>
                        </h4>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h3 class="card-title">@lang('cosmo.users.threads.total_post')</h3>
                    </div>
                    <div class="card-body">
                        <h4>
                            <span class="fa-stack fa-1x">
                                <i class="fad fa-square fa-stack-2x" style="color: var(--Accent_Background)"></i>
                                <i class="fad fa-message-dots fa-stack-1x" style="color: var(--Accent_Color)"></i>
                            </span>
                            <!--
                                TODO: Fix that shit and make it dynamic
                                <span>@lang('cosmo.users.threads.post', ['amount' => $i])</span>
                            -->
                            <span>2 posts</span>
                        </h4>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center">
                <div class="card border-top-accent shadow mb-3">
                    <div class="card-body">
                        <div class="d-inline">
                            <i class="fad fa-circle-exclamation fa-2x" style="color: var(--Accent_Color); font-size: 1.455rem"></i>
                        </div>
                        <div class="d-inline-block ml-2">
                            <h5>@lang('cosmo.users.no_thread', ['name' => $user->username])</h5>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{ $threads->links('themes.havart.pagination') }}
    </div>
@endsection