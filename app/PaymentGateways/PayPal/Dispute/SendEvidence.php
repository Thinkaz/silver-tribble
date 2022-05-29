<?php

namespace App\PaymentGateways\PayPal\Dispute;

use App\Models\Store\Order;
use App\PaymentGateways\PayPal\ApiClient;
use Barryvdh\DomPDF\Facade as PDF;
use GuzzleHttp\Client;
use PayPalHttp\HttpRequest;

class SendEvidence
{
    private string $disputeId;
    private Order $order;
    private Client $client;

    public function __construct(string $disputeId, Order $order)
    {
        $this->disputeId = $disputeId;
        $this->order = $order;
        $this->client = new Client();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function execute()
    {
        $client = ApiClient::getCheckoutClient();

        $pdf = PDF::loadView('pdf.evidence', [
            'order' => $this->order
        ]);

        $request = new HttpRequest('/v1/customer/disputes/'.$this->disputeId.'/provide-evidence', 'POST');
        $client->authInjector->inject($request);

        $baseUrl = $client->environment->baseUrl();

        $this->client->post("{$baseUrl}v1/customer/disputes/{$this->disputeId}/provide-evidence", [
            "headers" => [
                "Authorization" => $request->headers['Authorization'],
            ],
            "multipart" => [
                [
                    "name" => "evidence",
                    "contents" => $pdf->stream(),
                    "filename" => "evidence.pdf"
                ],
                [
                    "name" => "input",
                    "contents" => json_encode([
                        "evidences" => [
                            [
                                "evidence_type" => "RETURN_POLICY",
                                "notes" => trans("cosmo/store.evidence.note")
                            ]
                        ]
                    ]),
                    "headers" => [
                        "Content-Type" => "application/json"
                    ]
                ]
            ]
        ]);
    }
}
