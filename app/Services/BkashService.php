<?php

namespace App\Services;

use GuzzleHttp\Client;

class BkashService
{
    protected $client;
    protected $baseUrl;
    protected $appKey;
    protected $appSecret;
    protected $username;
    protected $password;
    protected $token;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = config('services.bkash.base_url');
        $this->appKey = config('services.bkash.app_key');
        $this->appSecret = config('services.bkash.app_secret');
        $this->username = config('services.bkash.username');
        $this->password = config('services.bkash.password');
        $this->token = $this->getToken();
    }

    protected function getToken()
    {
        $response = $this->client->post("{$this->baseUrl}/checkout/token/grant", [
            'headers' => [
                'Content-Type' => 'application/json',
                'username' => $this->username,
                'password' => $this->password,
            ],
            'json' => [
                'app_key' => $this->appKey,
                'app_secret' => $this->appSecret,
            ],
        ]);

        return json_decode($response->getBody())->id_token;
    }

    public function createPayment($amount, $invoice, $intent = "sale")
    {
        $response = $this->client->post("{$this->baseUrl}/checkout/payment/create", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'X-APP-Key' => $this->appKey,
            ],
            'json' => [
                'amount' => $amount,
                'currency' => 'BDT',
                'merchantInvoiceNumber' => $invoice,
                'intent' => $intent,
            ],
        ]);

        return json_decode($response->getBody());
    }

    public function executePayment($paymentID)
    {
        $response = $this->client->post("{$this->baseUrl}/checkout/payment/execute", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'X-APP-Key' => $this->appKey,
            ],
            'json' => [
                'paymentID' => $paymentID,
            ],
        ]);

        return json_decode($response->getBody());
    }

    public function queryPayment($paymentID)
    {
        $response = $this->client->post("{$this->baseUrl}/checkout/payment/query", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->token,
                'X-APP-Key' => $this->appKey,
            ],
            'json' => [
                'paymentID' => $paymentID,
            ],
        ]);

        return json_decode($response->getBody());
    }
}