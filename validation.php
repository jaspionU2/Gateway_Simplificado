<?php

require_once __DIR__ . "/payment.php";

class PaymentValidator
{
    public function __construct(
        public ModelPayment $payment
    ) {
        $reflectionClass = new ReflectionClass($payment);
        $methods = $reflectionClass->getMethods();

        foreach ($methods as $method) {
            try {
                $result = $method->invoke($payment);
                if (is_null($result)) throw new Exception("Erro: um ou mais campos do objeto {get_class($payment)} estão ausentes");
                
                switch ($method->getName()) { 
                    case "getPaymentAmount":
                        if ($result <= 0) {
                            throw new ValueError("O atributo 'paymentAmount' espera um inteiro positivo maior que zero.");
                        }
                        break;
                }
            } catch (ReflectionException $e) {
                throw new Exception($e->getMessage());
            }
        }
    }
}
