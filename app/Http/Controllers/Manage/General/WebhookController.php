<?php

namespace App\Http\Controllers\Manage\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\WebhookRequest;
use App\Models\Webhook;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class WebhookController extends Controller
{
    private static array $webhookTypes = [
        Webhook::TYPE_DISCORD => 'Discord',
        Webhook::TYPE_CUSTOM => 'Custom',
    ];

    private static array $triggerNames = [
        'thread.created' => 'Thread Created',
        'thread.action' => 'Thread Action Executed',
        'post.created' => 'Post Created',
        'order.succeeded' => 'Order Succeeded',
        'order.delivered' => 'Order Delivered',
        'changelog.created' => 'Changelog Created',
        'chat.message' => 'Chat Message Sent',
    ];

    public function __construct()
    {
        $this->middleware('permission:manage-webhooks');
    }

    public function index(): View
    {
        $webhooks = Webhook::all();

        return view('manage.general.webhooks', [
            'webhooks' => $webhooks,
            'webhookTypes' => self::$webhookTypes,
            'triggers' => self::$triggerNames,
        ]);
    }

    public function store(WebhookRequest $request): RedirectResponse
    {
        Webhook::create(
            $request->validated()
        );

        toastr()->success('Successfully created a new webhook!');

        return redirect()->back();
    }

    public function update(WebhookRequest $request, Webhook $webhook): RedirectResponse
    {
        $webhook->update($request->validated());

        toastr()->success('Successfully updated the webhook!');

        return redirect()->back();
    }

    public function destroy(Webhook $webhook): RedirectResponse
    {
        $webhook->delete();

        toastr()->success('Successfully deleted the webhook!');

        return redirect()->back();
    }
}
