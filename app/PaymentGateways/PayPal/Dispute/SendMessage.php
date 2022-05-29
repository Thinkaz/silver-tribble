<?php

namespace App\PaymentGateways\PayPal\Dispute;

use App\PaymentGateways\PayPal\ApiClient;
use PayPalHttp\HttpRequest;

class SendMessage
{
    private string $message;
    private string $dispute;

    public function __construct($dispute, $message)
    {
        $this->dispute = $dispute;
        $this->message = $message;
    }

    /**
     * @throws \PayPalHttp\HttpException
     * @throws \PayPalHttp\IOException
     */
    public function execute()
    {
        $request = new HttpRequest('/v1/customer/disputes/'.$this->dispute.'/send-message', 'POST');
        $request->headers['Content-Type'] = 'application/json';

        $request->body = [
            'message' => $this->message,
        ];

        ApiClient::getCheckoutClient()->execute($request);
    }
}