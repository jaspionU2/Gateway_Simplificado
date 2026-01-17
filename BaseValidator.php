<?php

require_once __DIR__ . "/DTOPayment.php";

class BaseValidator
{
    protected array $queueValidation = [];
    protected $error = [];

    public function enqueueValidation(array $validations) : self
    {
        foreach($validations as [$object, $method]) {
            $this->queueValidation[] = [$object, $method];
        }
        return $this;
    }

    public function execValidation(PaymentDTO $payment, bool $stepInTheFirstError) : void
    {
        foreach ($this->queueValidation as $callFunc) {
            try {
                call_user_func($callFunc, $payment);
            } catch (DomainException $e) {
                var_dump($e->getMessage());
                if ($stepInTheFirstError) break;
            }
        }
    }

    public function getErrors() : array
    {
        return $this->error;
    }

    public function hasErros() : bool
    {
        if (count($this->error)) return true;
        return false;
    }
}



?>