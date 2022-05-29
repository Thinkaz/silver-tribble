<?php

namespace App\PaymentGateways\PayPal\Order;

use App\Models\Store\Order;
use App\Models\Store\Transactions\PayPalTransaction;
use App\PaymentGateways\PayPal\ApiClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpException;

class CaptureOrder
{
    private Request $request;
    private PayPalTransaction $transaction;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->transaction = PayPalTransaction::whereOrderId($this->request->get('token'))->firstOrFail();
    }

    /**
     * @throws \PayPalHttp\HttpException
     * @throws \PayPalHttp\IOException
     */
    public function execute(): RedirectResponse
    {
        $request = new OrdersCaptureRequest($this->request->get('token'));

        try {
            $response = ApiClient::getCheckoutClient()->execute($request);
        } catch (HttpException $e) {
            $body = json_decode($e->getMessage());

            if ($body->name === 'UNPROCESSABLE_ENTITY' && $body->details[0]->issue === 'INSTRUMENT_DECLINED') {
                return redirect()->route('store.checkout.fail');
            } else {
                throw $e;
            }
        }

        $payer = $response->result->payer;

        $this->transaction->update([
            'transaction_id' => $response->result->purchase_units[0]->payments->captures[0]->id,
            'buyer_name' => $payer->name->given_name.' '.$payer->name->surname,
            'buyer_email' => $payer->email_address
        ]);

        $this->transaction->order()->update([
            'status' => Order::STATUS_PENDING,
        ]);

        return redirect()->route('store.checkout.success');
    }
}
