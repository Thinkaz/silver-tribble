@props(['main'])

{{-- TODO : Optimize maybe ? --}}
@if (isset($main->boards))
    @php
        $boards = $main->boards;
    @endphp
@elseif (isset($main->subBoards))
    @php
        $boards = $main->subBoards;
    @endphp
@endif

@foreach($boards as $board)
    <div class="card mb-0" id="board">
        <div class="d-flex">
            <div class="d-inline-flex flex-grow-1">
                <div class="d-inline-flex mr-4">
                    <div class="icon_holder my-auto">
                        <span class="fa-stack fa-2x">
                            <i class="fas fa-circle fa-stack-2x"
                               style="color: {{ $board->color }}; opacity: .05">
                            </i>
                            <i style="color: {{$board->color}};"
                               class="{{$board->icon}} fa-stack-1x">
                            </i>
                        </span>
                    </div>
                </div>
                <div class="d-inline-flex info my-auto ml-3">
                    <div class="content">
                        <a href="{{ route('forums.boards.show', $board->id) }}">
                            <h5 class="title mb-0 pb-0">{{ $board->name }}</h5>
                        </a>
                        <p class="description mb-0 pb-0">{{ truncate($board->description, 70) }}</p>
                    </div>
                </div>
            </div>

            <div class="d-inline-flex flex-shrink-0 latest_info">
                <div class="text-right d-flex my-auto" id="latest">
                    @if($board->latestThread)
                        <div class="my-auto overflow-hidden text-nowrap">
                            <div>
                                <a href="{{ route('forums.threads.show', $board->latestThread->id) }}">
                                    <h5 class="thread_title my-auto">{{ truncate($board->latestThread->title, 13) }}</h5>
                                </a>
                                <a href="{{ route('users.show', $board->latestThread->user->steamid) }}"
                                   class="thread_author my-auto" style="color: {{ $board->latestThread->user->displayRole->color  }}">- {{$board->latestThread->user->username}}
                                </a>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('users.show', $board->latestThread->user->steamid) }}">
                                <img src="{{$board->latestThread->user->avatar}}"
                                 class="d-none d-lg-block rounded-circle ml-2 profile-forum-image my-auto"
                                 alt="Thread User Image" style="width: 50px; height: 50px">
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
