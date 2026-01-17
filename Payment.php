<?php

readonly class ModelPayment
{
    private ?string $paymentAmount;
    private ?string $coin;
    private ?string $idOrder;
    private ?string $paymentMethod;
    private ?array $extraLoad;

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
}
?>
