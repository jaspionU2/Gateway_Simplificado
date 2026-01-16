<?php

require_once __DIR__ . "/payment.php";
require_once __DIR__ . "/DTOPayment.php";

class PaymentValidator
{
    private array $queueValidation = [];

    public function __construct() {}

    public function enqueueValidation()
    {
        $this->queueValidation = [
            [PaymentValidator::class, 'assertMethodExist'],
            [PaymentValidator::class, 'assertPositiveAmount'],
            [PaymentValidator::class, 'assertCoinExist'],
            [PaymentValidator::class, 'assertOrderIdExist'],
        ];
        return $this->queueValidation;
    }

    public function execValidation(PaymentDTO $payment) 
    {
        foreach($this->queueValidation as $callFunc) {
            try {
                call_user_func(array($callFunc[0], $callFunc[1]), $payment);
            } catch (DomainException $e) {
                var_dump($e->getMessage());
            }
        }
    }

    private function assertPositiveAmount(PaymentDTO $payment): void
    {
        if ($payment->paymentAmount <= 0) {
            throw new DomainException(
                "A quantia para pagamento deve ser maior que zero, valor atual é:  {$payment->paymentAmount}",
            );
        }
    }

    private function assertOrderIdExist(PaymentDTO $payment): void
    {
        if (is_null($payment->idOrder)) {
            throw new DomainException("O identificador de pedido de pagamento não foi informado.");
        }
    }

    private function assertCoinExist(PaymentDTO $payment): void
    {
        if (is_null($payment->coin)) {
            throw new DomainException("A moeda deve ser informada");
        }
    }

    private function assertMethodExist(PaymentDTO $payment): void
    {
        $validMethods = ["pix", "credit_card", "debit_card"];
        $methodIsValid = array_any($validMethods, fn($value) => $payment->paymentMethod == $value);

        if (is_null($payment->paymentMethod) || !$methodIsValid) {
            throw new DomainException("O método de pagamento deve ser informado e ser valido.");
        }
    }
}
$json = json_encode([
    "paymentAmount" => "105.50",
    "coin" => "BRL",
    "idOrder" => "ORD123456",
    "paymentMethod" => "",
    "extraLoad" => ["description" => "Produto A"]
]);

$payment = PaymentDTO::fromJson($json);
$paymentValidator = new PaymentValidator;
$paymentValidator->enqueueValidation();
$paymentValidator->execValidation($payment)
?>
