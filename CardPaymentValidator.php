<?php

require_once __DIR__ . "/BaseValidator.php";

class CardPaymentValidator extends BaseValidator
{
    public function validate(PaymentDTO $payment) : void
    {
        $this->enqueueValidation([
            [$this, 'assertCardNumber'],
            [$this, 'assertCardExpireDate'],
            [$this, 'assertCardCvv']
        ]);
        $this->execValidation($payment, false);
    }

    protected function assertCardNumber(PaymentDTO $payment) : void
    {
        $cardNumber = $payment->extraLoad['cardNumber'] ?? null;

        $cardNumberRegex = '/^\d{13,19}$/';
        $cardNumberClean = preg_replace('/[\s-]/', '', $cardNumber);

        if (!preg_match($cardNumberRegex, $cardNumberClean, $matches)) {
            throw new DomainException("O formato do numero de cartão informado é invalido: $cardNumber");
        }
    }

    protected function assertCardExpireDate(PaymentDTO $payment) : void
    {
        $cardExpireDate = $payment->extraLoad['cardExpireDate'] ?? null;
        $cardExpireDateRegex = '/^(0[1-9]|1[0-2])\/\d{2}$/';

        if (!preg_match($cardExpireDateRegex, $cardExpireDate, $matches)) {
            throw new DomainException("Formato da data de vencimento do cartão invalida: $cardExpireDate");
        }

        [$month, $year] = explode('/', $cardExpireDate);
        $actualDateTimestamp = strtotime(date('y-m-01'));
        $expireDateTimestamp = strtotime("$year-$month-01");

        if ($actualDateTimestamp > $expireDateTimestamp) {
            throw new DomainException("Cartão esta vencido: $cardExpireDate");
        }
    }

    protected function assertCardCvv(PaymentDTO $payment)
    {
        $cardCvvNumber = $payment->extraLoad['cardCvv'] ?? null;
        $cardCvvNumberRegex = '/^\d{3,4}$/';

        if (!preg_match($cardCvvNumberRegex, $cardCvvNumber)) {
            throw new DomainException("Formato do numero de Cvv do cartão é invalido: $cardCvvNumber");
        }
    }
}
?>