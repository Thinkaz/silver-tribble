<?php

namespace App\Http\Controllers\Forums;

use App\Events\ChatMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendChatMessageRequest;
use App\Models\ChatMessage;
use App\Support\WordFilterService;
use Carbon\Carbon;
use Closure;
use DateTimeZone;
use Illuminate\Http\Request;

class ChatboxController extends Controller
{
    private WordFilterService $wordFilterService;

    public function __construct(WordFilterService $wordFilterService)
    {
        $this->wordFilterService = $wordFilterService;

        $this->middleware(function (Request $request, Closure $next) {
            if (!config('cosmo.configs.forums_chatbox_enabled')) {
                abort(404);
            }

            return $next($request);
        });
    }

    public function index(Request $request): array
    {
        $query = ChatMessage::with('user', 'user.displayRole')
            ->orderByDesc('created_at')
            ->limit(50);

        if ($request->has('since')) {
            $since = Carbon::createFromTimestamp($request->input('since'))
                ->timezone(config('app.timezone'));

            $query->where('created_at', '>=', $since);
        }

        return $query->get()->reverse()->map(fn (ChatMessage $chatMessage) => [
            'id' => $chatMessage->id,
            'message' => $chatMessage->message,
            'user' => $chatMessage->user->only('id', 'steamid', 'username', 'avatar', 'displayRole'),
            'created_at' => $chatMessage->created_at->diffForHumans(),
        ])->values()->toArray();
    }

    public function store(SendChatMessageRequest $request): array
    {
        $message = $request->input('message');
        if (config('cosmo.configs.forums_chatbox_word_filter', true)) {
            $message = $this->wordFilterService->filterWords($message);
        }

        $chatMessage = $request->user()->chatMessages()->create([
            'message' => $message,
        ]);

        ChatMessageSent::dispatch($chatMessage);

        return [
            'success' => true,
        ];
    }
}
