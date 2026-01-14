<?php

require_once __DIR__ . "/payment.php";
require_once __DIR__ . "/DTOPayment.php";

class PaymentValidator
{
    private $queueValidation;

    public function __construct(
        public PaymentDTO $payment
    ){}

    


}
