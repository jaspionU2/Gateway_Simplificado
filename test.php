<?php

require_once __DIR__ . "/DTOPayment.php";
require_once __DIR__ . "/GenericPaymentValidator.php";
require_once __DIR__ . "/CardPaymentValidator.php";

echo "=== TESTE DO GATEWAY SIMPLIFICADO ===" . PHP_EOL . PHP_EOL;

// ========================================
// TESTE 1: Pagamento PIX válido
// ========================================
echo "🧪 TESTE 1: Pagamento PIX válido" . PHP_EOL;
$jsonPix = json_encode([
    "paymentAmount" => "150.00",
    "coin" => "BRL",
    "idOrder" => "ORD123456",
    "paymentMethod" => "pix",
    "extraLoad" => ["description" => "Pagamento via PIX"]
]);

$paymentPix = PaymentDTO::fromJson($jsonPix);
$validator = new GenericPaymentValidator();
$validator->validate($paymentPix);
echo "✅ Pagamento PIX processado!" . PHP_EOL . PHP_EOL;

// ========================================
// TESTE 2: Pagamento com erros (valor zerado)
// ========================================
echo "🧪 TESTE 2: Pagamento com valor inválido" . PHP_EOL;
$jsonInvalid = json_encode([
    "paymentAmount" => "0",
    "coin" => "BRL",
    "idOrder" => "ORD789",
    "paymentMethod" => "pix",
    "extraLoad" => []
]);

$paymentInvalid = PaymentDTO::fromJson($jsonInvalid);
$validatorInvalid = new GenericPaymentValidator();
$validatorInvalid->validate($paymentInvalid);
echo PHP_EOL;

// ========================================
// TESTE 3: Pagamento com método inválido
// ========================================
echo "🧪 TESTE 3: Método de pagamento inválido" . PHP_EOL;
$jsonBadMethod = json_encode([
    "paymentAmount" => "100.00",
    "coin" => "BRL",
    "idOrder" => "ORD999",
    "paymentMethod" => "boleto", // método não suportado
    "extraLoad" => []
]);

$paymentBadMethod = PaymentDTO::fromJson($jsonBadMethod);
$validatorBadMethod = new GenericPaymentValidator();
$validatorBadMethod->validate($paymentBadMethod);
echo PHP_EOL;

// ========================================
// TESTE 4: Pagamento com cartão de crédito
// ========================================
echo "🧪 TESTE 4: Pagamento com cartão de crédito" . PHP_EOL;
$jsonCard = json_encode([
    "paymentAmount" => "299.90",
    "coin" => "BRL",
    "idOrder" => "ORD555",
    "paymentMethod" => "credit_card",
    "extraLoad" => [
        "cardNumber" => "4111111111111111",
        "cardExpireDate" => "12/25",
        "cardCvv" => "123"
    ]
]);

$paymentCard = PaymentDTO::fromJson($jsonCard);
$cardValidator = new CardPaymentValidator();
$cardValidator->validate($paymentCard);
echo "✅ Cartão validado!" . PHP_EOL . PHP_EOL;

// ========================================
// TESTE 5: Cartão inválido
// ========================================
echo "🧪 TESTE 5: Cartão com número inválido" . PHP_EOL;
$jsonBadCard = json_encode([
    "paymentAmount" => "50.00",
    "coin" => "BRL",
    "idOrder" => "ORD666",
    "paymentMethod" => "credit_card",
    "extraLoad" => [
        "cardNumber" => "1234", // número muito curto
        "cardExpireDate" => "01/2025", // expirado
        "cardCvv" => "12"
    ]
]);

$paymentBadCard = PaymentDTO::fromJson($jsonBadCard);
$badCardValidator = new CardPaymentValidator();
$badCardValidator->validate($paymentBadCard);
echo PHP_EOL;

echo "=== FIM DOS TESTES ===" . PHP_EOL;
