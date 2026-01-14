<?php 

readonly class PaymentDTO
{
    public function __construct(
        public ?string $paymentAmount,
        public ?string $coin,
        public ?string $idOrder,
        public ?string $paymentMethod,
        public ?array $extraLoad,
    ){}

    public static function fromJson(string $json)
    {
        if (!json_validate($json)) return false;

        $decodedJson = json_decode($json, true);

        return new self (
           paymentAmount: $decodedJson['paymentAmount'] ?? null,
           coin: $decodedJson['coin'] ?? null,
           idOrder: $decodedJson['idOrder'] ?? null,
           paymentMethod: $decodedJson['paymentMethod'] ?? null,
           extraLoad: $decodedJson['extraLoad'] ?? null,
        );
    }
}
?>