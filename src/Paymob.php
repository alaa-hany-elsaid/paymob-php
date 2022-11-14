<?php


/**
 * Class: Paymob
 * @package Alaa\Paymob
 * @author Alaa Elsaid <elboray.alaa1@icloud.com>
 * Date: 11/14/22
 * Time: 1:20 AM
 */

namespace Alaa\Paymob;

use GuzzleHttp\Exception\GuzzleException;
use  Exception;

class Paymob
{
    private static ?PaymobClient $paymobClient = null;
    private static array $config = [];
    private static array $authToken = [
        "token" => null,
        "created_at" => 0
    ];


    /**
     * @param PaymobClient $paymobClient
     */
    public static function setPaymobClient(PaymobClient $paymobClient): void
    {
        self::$paymobClient = $paymobClient;
    }

    /**
     * @return PaymobClient
     */
    public static function getPaymobClient(): PaymobClient
    {
        if (self::$paymobClient == null) self::$paymobClient = new PaymobClient();
        return self::$paymobClient;
    }

    /**
     * @return array
     */
    public static function getConfig(): array
    {
        return self::$config;
    }

    /**
     * @param array $config
     */
    public static function setConfig(array $config): void
    {
        self::$config = $config;
    }


    /**
     * @return string
     * @throws GuzzleException
     * @throws Exception
     */
    public static function getAuthToken(): string
    {
        if (self::$authToken['token'] == null || (time() - self::$authToken['created_at'] >= (60 * 60 - 30))) {
            if (isset(self::$config['api_key']) && strlen(self::$config['api_key']) > 10)
                self::$authToken = ['token' => self::getPaymobClient()->generateAuthToken(self::$config['api_key']), "created_at" => time()];
            else
                throw  new Exception("please set a valid api_key");
        }
        return self::$authToken['token'];
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public static function card($amount, $billing_data, $items = [], $delivery_needed = false, $additional_info = [], $lock_order_when_paid = false): array
    {
        if (!(isset(self::$config['cards_iframe']) || isset(self::$config['default_iframe']))) throw  new Exception("please set a valid cards iframe or default iframe");
        if (!(isset(self::$config['card_integration']))) throw  new Exception("please set a valid card_integration");
        $res = self::generatePaymentKeyWithOrderId(self::$config['card_integration'], $billing_data, $amount, $items, $delivery_needed, $additional_info, $lock_order_when_paid);
        $res["url"] = "https://accept.paymobsolutions.com/api/acceptance/iframes/" . (self::$config['cards_iframe'] ?? self::$config['default_iframe']) . "?payment_token=" . $res["payment_key"];
        return $res;
    }


    /**
     * @throws GuzzleException
     */
    public static function mobileWallet($wallet, $amount, $billing_data, $items = [], $delivery_needed = false, $additional_info = [], $lock_order_when_paid = false): array
    {
        if (!(isset(self::$config['wallet_integration']))) throw  new Exception("please set a valid wallet_integration");
        $res = self::generatePaymentKeyWithOrderId(self::$config['wallet_integration'], $billing_data, $amount, $items, $delivery_needed, $additional_info, $lock_order_when_paid);

        $res['url'] = self::getPaymobClient()->json("post", Routes::Payment, [
            "json" => [
                "source" => [
                    "identifier" => $wallet,
                    "subtype" => "WALLET"
                ],
                "payment_token" => $res["payment_key"]
            ]
        ])->redirect_url;
        return $res;
    }


    /**
     * @throws GuzzleException
     */
    private static function generatePaymentKeyWithOrderId($integration_id, $billing_data, $amount, $items = [], $delivery_needed = false, $additional_info = [], $lock_order_when_paid = false): array
    {
        $order = self::getPaymobClient()->registerOrder(self::getAuthToken(), array_merge([
            "amount_cents" => $amount,
            "items" => $items,
            "delivery_needed" => $delivery_needed,

        ], $additional_info));
        return ["payment_key" => self::getPaymobClient()->generatePaymentKey(self::getAuthToken(), $order->id, $integration_id, $billing_data, [
            "amount" => $amount,
            "expiration" => self::$config["expiration"] ?? 60 * 30,
            "currency" => self::$config['currency'] ?? "EGP",
            "lock_order_when_paid" => $lock_order_when_paid
        ]),
            "order_id" => $order->id

        ];
    }


}