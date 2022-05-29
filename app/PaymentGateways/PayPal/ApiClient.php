<?php

namespace App\PaymentGateways\PayPal;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class ApiClient
{
    public static function getCheckoutClient(): PayPalHttpClient
    {
        return new PayPalHttpClient(static::getCheckoutEnvironment());
    }

    private static function getCheckoutEnvironment()
    {
        $clientId = config('cosmo.configs.paypal_client_id');
        $clientSecret = config('cosmo.configs.paypal_client_secret');

        if (config('cosmo.configs.paypal_sandbox_enabled')) {
            return new SandboxEnvironment($clientId, $clientSecret);
        } else {
            return new ProductionEnvironment($clientId, $clientSecret);
        }
    }
}