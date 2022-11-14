<?php


/**
 * Class: Routes
 * @package Alaa\Paymob
 * @author Alaa Elsaid <elboray.alaa1@icloud.com>
 * Date: 11/13/22
 * Time: 11:49 PM
 */

namespace Alaa\Paymob;

class Routes
{
    public const Base = " https://accept.paymob.com/api/";
    public const  Authentication = "auth/tokens";
    public const  Order_Registration = "ecommerce/orders";
    public const  Payment_Key_Request = "acceptance/payment_keys";
    public const  Payment = "acceptance/payments/pay";
}