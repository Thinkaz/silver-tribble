<?php

namespace App\PaymentGateways\PayPal\Webhook;

use App\Models\Store\Order;
use App\Models\Store\Transactions\PayPalTransaction;
use App\PaymentGateways\PayPal\Dispute\SendEvidence;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DisputeEvent
{
    private object $body;
    private PayPalTransaction $transaction;

    /**
     * DisputeEvent constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->body = json_decode($request->getContent());

        $transactionId = $this->body->resource->disputed_transactions[0]->seller_transaction_id;
        $this->transaction = PayPalTransaction::whereTransactionId($transactionId)->firstOrFail();
    }

    public function execute()
    {
        $event = $this->body->event_type;
        if ($event === 'CUSTOMER.DISPUTE.CREATED') {
            $this->onDisputeCreated();
        } else if ($event === 'CUSTOMER.DISPUTE.UPDATED') {
            $this->onDisputeUpdated();
        }
    }

    private function onDisputeCreated()
    {
        $this->transaction->order->buyer->ban('PayPal Chargeback');
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function onDisputeUpdated()
    {
        if ($this->body->resource->status !== "WAITING_FOR_SELLER_RESPONSE") return;

        $lifeCycle = $this->body->resource->dispute_life_cycle_stage;
        $disputeId = $this->body->resource->dispute_id;
        $model = null;

        if ($lifeCycle === "INQUIRY") {
            //$model = new SendMessage($disputeId, "");
        } elseif ($lifeCycle === "CHARGEBACK") {
            $model = new SendEvidence($disputeId, $this->transaction->order);
        } else return;

        $model->execute();
    }
}
