<?php

class ModelPayment
{
    private $paymentAmount = null;
    private $coin = null;
    private $idOrder = null;
    private $paymentMethod = null;
    private $extraLoad = null;

    public $JSONPayment;

    public function __construct($JSONPayment)
    {
        $this->JSONPayment = $JSONPayment;
    }

    private function decodeJson(): array | null
    {
        if (json_validate($this->JSONPayment)) return null;

        $decodedJson = json_decode($this->JSONPayment, true);

        return $decodedJson;
    }

    public function normalize()
    {
        $decodedJson = $this->decodeJson();
        $reflectionClass = new ReflectionClass(ModelPayment::class);

        foreach ($decodedJson as $key => $value) {
            $propertyName = $key;
            if ($reflectionClass->hasProperty($propertyName)) {
                $property = $reflectionClass->getProperty($propertyName);
                $property->setValue($this, $value);
            }
        }
    }
}
