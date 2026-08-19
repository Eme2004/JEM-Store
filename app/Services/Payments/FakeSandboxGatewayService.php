<?php

namespace App\Services\Payments;

use Illuminate\Support\Str;

/**
 * Se usa automáticamente mientras no haya credenciales reales de Braintree
 * Sandbox configuradas (BRAINTREE_PRIVATE_KEY vacío), para que el checkout
 * y sus tests funcionen sin depender de una cuenta externa. En cuanto se
 * configuran las credenciales reales, AppServiceProvider cambia a
 * BraintreeGatewayService automáticamente: nada más en el código cambia.
 *
 * Los nonces reconocidos replican los "fake nonces" que Braintree documenta
 * oficialmente para probar sin Hosted Fields, así que el comportamiento es
 * el mismo que tendría el sandbox real ante esos mismos valores.
 */
class FakeSandboxGatewayService implements PaymentGatewayContract
{
    private const DECLINED_NONCES = [
        'fake-processor-declined-visa-nonce',
        'fake-processor-declined-mastercard-nonce',
        'fake-processor-declined-amex-nonce',
    ];

    private const CARD_BRANDS = [
        'fake-valid-visa-nonce' => 'Visa',
        'fake-valid-mastercard-nonce' => 'Mastercard',
        'fake-valid-amex-nonce' => 'American Express',
    ];

    public function clientToken(): string
    {
        return 'fake-sandbox-client-token-'.Str::random(16);
    }

    public function sale(string $paymentMethodNonce, float $amount, array $options = []): PaymentResult
    {
        if (in_array($paymentMethodNonce, self::DECLINED_NONCES, true)) {
            return new PaymentResult(
                approved: false,
                gateway: 'braintree-fake',
                environment: 'sandbox',
                message: 'El pago fue rechazado por el banco (simulado).',
            );
        }

        return new PaymentResult(
            approved: true,
            gateway: 'braintree-fake',
            environment: 'sandbox',
            transactionId: 'fake-txn-'.Str::random(10),
            cardBrand: self::CARD_BRANDS[$paymentMethodNonce] ?? 'Visa',
            cardLast4: '1111',
        );
    }
}
