<?php

namespace App\Http\Controllers\Api\Game;

use App\Events\OrderDelivered;
use App\Http\Controllers\Controller;
use App\Models\Index\Server;
use App\Models\Store\Action;
use App\Models\Store\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreController extends Controller
{
    public function pending(Request $request)
    {
        $server = $request->user();
        if (!($server instanceof Server)) {
            return response(null, 400);
        }

        return [
            'orders' => Order::query()
                ->where('status', Order::STATUS_WAITING_FOR_PACKAGE)
                ->whereHas('package.servers', function (Builder $query) use ($server) {
                    $query->where('id', $server->id);
                })
                ->with('package', function (BelongsTo $query) {
                    $query->select(['id', 'name']);
                })
                ->with('actions', function (HasMany $query) {
                    $query->where([
                        ['delivered_at', null],
                        ['active', false],
                    ])->select(['id', 'order_id', 'name', 'receiver', 'data']);
                })->get(['id', 'receiver', 'package_id']),

            'actions' => Action::query()->where([
                ['expires_at', '<', now()],
                ['active', true]
            ])->whereHas('order.package.servers', function (Builder $query) use ($server) {
                $query->where('id', $server->id);
            })->get(['id', 'name', 'data', 'receiver']),
        ];
    }

    public function weapons(string $sid64): array
    {
        $actions = Action::query()->where([
            ['receiver', $sid64],
            ['active', true],
            ['name', 'weapons'],
        ])->get();

        return $actions->filter(function (Action $action) {
            return array_key_exists('perm', $action->data) && $action->data['perm'] === '1';
        })->values()->toArray();
    }

    public function completeAction(Action $action): Response
    {
        $belongsToServer = $action->order()->has('package.servers', request()->user()->id);

        if (!$belongsToServer || $action->active || $action->delivered_at !== null) {
            return response(null, 401);
        }

        $action->update([
            'delivered_at' => now(),
            'active' => true,
        ]);

        return response()->noContent();
    }

    public function deliverOrder(Order $order): Response
    {
        $belongsToServer = $order->package()->has('servers', request()->user()->id);
        if (!$belongsToServer || $order->status !== Order::STATUS_WAITING_FOR_PACKAGE) {
            return response(null, 401);
        }

        $order->update([
            'status' => Order::STATUS_DELIVERED,
        ]);

        event(new OrderDelivered($order));

        return response()->noContent();
    }

    public function expireAction(Action $action): Response
    {
        $belongsToServer = $action->order()->has('package.servers', request()->user()->id);
        if (!$belongsToServer || !$action->active) {
            return response(null, 401);
        }

        $action->update([
            'active' => false,
        ]);

        return response()->noContent();
    }
}
