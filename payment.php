<?php

class ModelPayment
{
    private ?string $paymentAmount = null;
    private ?string $coin = null;
    private ?string $idOrder = null;
    private ?string $paymentMethod = null;
    private ?array $extraLoad = null;

    private function decodeJson($jsonPayment): ?array
    {
        if (!json_validate($jsonPayment)) return null;

        return json_decode($jsonPayment, true);
    }

    public function normalize($jsonPayment): void
    {
        $decodedJson = $this->decodeJson($jsonPayment);
        $reflectionClass = new ReflectionClass(ModelPayment::class);

        if (count($decodedJson) <= 0) throw new Exception("O array não possui valores");

        foreach ($decodedJson as $key => $value) {
            $propertyName = $key;
            if ($reflectionClass->hasProperty($propertyName)) {
                $property = $reflectionClass->getProperty($propertyName);
                $property->setValue($this, $value);
            }
        }
    }

    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    public function getCoin()
    {
        return $this->coin;
    }

    public function getIdOrder()
    {
        return $this->idOrder;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function getExtraLoad()
    {
        return $this->extraLoad;
    }
}
?>
