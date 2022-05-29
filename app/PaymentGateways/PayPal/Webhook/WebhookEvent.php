<?php

namespace App\PaymentGateways\PayPal\Webhook;

use App\PaymentGateways\PayPal\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PayPalHttp\HttpRequest;
use Symfony\Component\HttpFoundation\HeaderBag;

class WebhookEvent
{
    private HeaderBag $headers;
    private Request $request;

    public function __construct(Request $request)
    {
        $this->headers = $request->headers;
        $this->request = $request;
    }

    /**
     * @throws \PayPalHttp\HttpException
     * @throws \PayPalHttp\IOException
     */
    private function verify(): bool
    {
        $request = new HttpRequest('/v1/notifications/verify-webhook-signature', 'POST');
        $request->headers['Content-Type'] = 'application/json';

        $request->body = [
            'auth_algo' => $this->headers->get('PAYPAL-AUTH-ALGO'),
            'cert_url' => $this->headers->get('PAYPAL-CERT-URL'),
            'transmission_id' => $this->headers->get('PAYPAL-TRANSMISSION-ID'),
            'transmission_sig' => $this->headers->get('PAYPAL-TRANSMISSION-SIG'),
            'transmission_time' => $this->headers->get('PAYPAL-TRANSMISSION-TIME'),
            'webhook_id' => config('cosmo.configs.paypal_webhook_id'),
            'webhook_event' => $this->request->json()->all(),
        ];

        $response = ApiClient::getCheckoutClient()->execute($request);

        return $response->statusCode === 200 && $response->result->verification_status === 'SUCCESS';
    }

    /**
     * Execute the webhook with the corresponding event class
     *
     * @throws \PayPalHttp\HttpException
     * @throws \PayPalHttp\IOException
     */
    public function execute()
    {
        if (!$this->verify()) abort(404);

        $res_type = $this->request->get('resource_type');
        $res = null;

        switch ($res_type) {
            case 'capture':
                $res = new CaptureEvent($this->request);
                break;
            case 'dispute':
                $res = new DisputeEvent($this->request);
                break;
            default:
                abort(200);
        }

        $res->execute();
    }
}
