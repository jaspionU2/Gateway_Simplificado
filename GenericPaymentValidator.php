<?php

require_once __DIR__ . "/BaseValidator.php";

class GenericPaymentValidator extends BaseValidator
{
    public function validate(PaymentDTO $payment) 
    {
        $this->enqueueValidation([
            [$this, 'assertMethodExist'],
            [$this, 'assertPositiveAmount'],
            [$this, 'assertCoinExist'],
            [$this, 'assertOrderIdExist']
        ]);
        $this->execValidation($payment, false);
    }

    protected function assertPositiveAmount(PaymentDTO $payment): void
    {
        if ($payment->paymentAmount <= 0) {
            throw new DomainException(
                "A quantia para pagamento deve ser maior que zero, valor atual é:  {$payment->paymentAmount}",
            );
        }
    }

    protected function assertOrderIdExist(PaymentDTO $payment): void
    {
        if (is_null($payment->idOrder)) {
            throw new DomainException("O identificador de pedido de pagamento não foi informado.");
        }
    }

    protected function assertCoinExist(PaymentDTO $payment): void
    {
        if (is_null($payment->coin)) {
            throw new DomainException("A moeda deve ser informada");
        }
    }

    protected function assertMethodExist(PaymentDTO $payment): void
    {
        $validMethods = ["pix", "credit_card", "debit_card"];
        $methodIsValid = array_any($validMethods, fn($value) => $payment->paymentMethod == $value);

        if (is_null($payment->paymentMethod) || !$methodIsValid) {
            throw new DomainException("O método de pagamento deve ser informado e ser valido.");
        }
    }
}

$json = json_encode([
    "paymentAmount" => "",
    "coin" => "BRL",
    "idOrder" => "ORD123456",
    "paymentMethod" => "pix",
    "extraLoad" => ["description" => "Produto A"]
]);

$payment = PaymentDTO::fromJson($json);
$paymentValidator = new GenericPaymentValidator;
$paymentValidator->validate($payment)
?>
