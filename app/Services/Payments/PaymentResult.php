<?php

namespace App\Services\Payments;

readonly class PaymentResult
{
    public function __construct(
        public bool $approved,
        public string $gateway,
        public string $environment,
        public ?string $transactionId = null,
        public ?string $cardBrand = null,
        public ?string $cardLast4 = null,
        public ?string $message = null,
    ) {
    }
}
