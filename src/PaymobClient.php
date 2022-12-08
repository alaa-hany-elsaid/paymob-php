<?php


/**
 * Class: PaymobClient
 * @package Alaa\Paymob
 * @author Alaa Elsaid <elboray.alaa1@icloud.com>
 * Date: 11/13/22
 * Time: 11:47 PM
 */

namespace Alaa\Paymob;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class PaymobClient
{

     const VERSION = "1.0.0";
    private  $client;


    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => Routes::Base,
            'headers' => [
                'User-Agent' => "paymob_client/" . self::VERSION,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }


    /**
     * @throws GuzzleException
     */
    public function generateAuthToken($api_key)
    {
        return $this->json("post", Routes::Authentication, [
            "json" => [
                "api_key" => $api_key
            ]
        ])->token;
    }


    /**
     * @throws GuzzleException
     */
    public function registerOrder($auth_token, $info = [])
    {
        return $this->json("post", Routes::Order_Registration, [
            "json" => array_merge([
                "auth_token" => $auth_token,
                "delivery_needed" => false,
                "amount_cents" => 0,
                "currency" => "EGP",
            ], $info)
        ]);
    }


    /**
     * @throws GuzzleException
     */
    public function generatePaymentKey($auth_token, $order_id, $integration_id, $billing_data, $info = [])
    {
        return $this->json("post", Routes::Payment_Key_Request, [
            "json" => array_merge([
                "auth_token" => $auth_token,
                "order_id" => $order_id,
                "integration_id" => $integration_id,
                "billing_data" => $billing_data,
                "expiration" => 60 * 30,
                "currency" => "EGP",
            ], $info)
        ])->token;

    }






    /**
     * @return Client
     */
    public function getGClient()
    {
        return $this->client;
    }


    /**
     * @throws GuzzleException
     */
    public function json($method, $uri, $options = [])
    {
        return json_decode($this->request($method, $uri, $options)->getBody()->getContents());

    }


    /**
     * @throws GuzzleException
     */
    public function request($method, $uri, $options = [])
    {
        return $this->getGClient()->request($method, $uri, $options);

    }


}