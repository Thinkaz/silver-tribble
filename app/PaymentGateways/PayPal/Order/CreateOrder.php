<?php

namespace App\PaymentGateways\PayPal\Order;

use App\Models\Store\Order;
use App\Models\Store\Package;
use App\Models\Store\Transactions\PayPalTransaction;
use App\Models\User;
use App\PaymentGateways\PayPal\ApiClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class CreateOrder
{
    private Package $package;
    private User $buyer;
    private string $receiver;
    private float $price;

    public function __construct(Package $package, User $buyer, string $receiver, float $price)
    {
        $this->package = $package;
        $this->buyer = $buyer;
        $this->receiver = $receiver;
        $this->price = $price;
    }

    /**
     * @throws \PayPalHttp\HttpException
     * @throws \PayPalHttp\IOException
     */
    public function execute(): RedirectResponse
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = $this->getRequestBody();

        $order = ApiClient::getCheckoutClient()->execute($request);

        PayPalTransaction::create([
            'order_id' => $order->result->id
        ])->order()->create([
            'price' => $this->price,
            'ip_address' => request()->ip(),
            'receiver' => $this->receiver,
            'package_id' => $this->package->id,
            'buyer_id' => $this->buyer->id,
            'status' => Order::STATUS_WAITING_FOR_PAYMENT,
        ]);

        return redirect()->to(
            $order->result->links[1]->href
        );
    }

    private function getRequestBody(): array
    {
        $currency = config('cosmo.configs.store_currency');
        $price = (string) round($this->price, 2);

        Log::debug($price);

        return [
            'intent' => 'CAPTURE',
            'application_context' => [
                'brand_name' => config('app.name'),
                'cancel_url' => route('store.checkout.cancel'),
                'return_url' => route('store.checkout.paypal.capture'),
                'shipping_preference' => 'NO_SHIPPING'
            ],
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => $price,
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => $currency,
                                'value' => $price,
                            ]
                        ]
                    ],
                    'items' => [
                        [
                            'name' => $this->package->name,
                            'unit_amount' => [
                                'currency_code' => $currency,
                                'value' => $price,
                            ],
                            'quantity' => 1,
                        ]
                    ]
                ]
            ]
        ];
    }
}
