<?php

namespace App\Services\Payments;

interface PaymentGatewayContract
{
    /**
     * Token que el frontend usa para inicializar Braintree Hosted Fields.
     * Nunca contiene datos de tarjeta: solo autoriza al navegador a
     * tokenizar directamente con Braintree.
     */
    public function clientToken(): string;

    /**
     * Cobra $amount (en la moneda configurada) usando un nonce ya
     * tokenizado por el frontend. $amount SIEMPRE se calcula en el
     * servidor a partir del pedido real, nunca se acepta desde el cliente.
     */
    public function sale(string $paymentMethodNonce, float $amount, array $options = []): PaymentResult;
}
