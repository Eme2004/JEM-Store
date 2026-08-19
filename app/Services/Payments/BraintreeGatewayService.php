<?php

namespace App\Services\Payments;

use Braintree\Exception as BraintreeException;
use Braintree\Gateway;
use Braintree\Transaction;

class BraintreeGatewayService implements PaymentGatewayContract
{
    private Gateway $gateway;

    private string $environment;

    public function __construct(array $config)
    {
        $this->environment = $config['environment'] ?? 'sandbox';

        $this->gateway = new Gateway([
            'environment' => $this->environment,
            'merchantId' => $config['merchant_id'],
            'publicKey' => $config['public_key'],
            'privateKey' => $config['private_key'],
        ]);
    }

    public function clientToken(): string
    {
        return $this->gateway->clientToken()->generate();
    }

    public function sale(string $paymentMethodNonce, float $amount, array $options = []): PaymentResult
    {
        $attribs = [
            'amount' => number_format($amount, 2, '.', ''),
            'paymentMethodNonce' => $paymentMethodNonce,
            'options' => [
                'submitForSettlement' => true,
            ],
        ];

        if (! empty($options['merchant_account_id'])) {
            $attribs['merchantAccountId'] = $options['merchant_account_id'];
        }

        try {
            $result = $this->gateway->transaction()->sale($attribs);
        } catch (BraintreeException $e) {
            return new PaymentResult(
                approved: false,
                gateway: 'braintree',
                environment: $this->environment,
                message: 'No se pudo contactar la pasarela de pago. Intenta de nuevo.',
            );
        }

        if ($result->success) {
            /** @var Transaction $transaction */
            $transaction = $result->transaction;

            $approved = in_array($transaction->status, [
                Transaction::AUTHORIZED,
                Transaction::SUBMITTED_FOR_SETTLEMENT,
                Transaction::SETTLING,
                Transaction::SETTLED,
            ], true);

            return new PaymentResult(
                approved: $approved,
                gateway: 'braintree',
                environment: $this->environment,
                transactionId: $transaction->id,
                cardBrand: $transaction->creditCardDetails->cardType ?? null,
                cardLast4: $transaction->creditCardDetails->last4 ?? null,
                message: $approved ? null : "Transacción en estado \"{$transaction->status}\".",
            );
        }

        $transaction = $result->transaction ?? null;

        return new PaymentResult(
            approved: false,
            gateway: 'braintree',
            environment: $this->environment,
            transactionId: $transaction->id ?? null,
            cardBrand: $transaction->creditCardDetails->cardType ?? null,
            cardLast4: $transaction->creditCardDetails->last4 ?? null,
            message: $result->message ?: 'El pago fue rechazado por el banco.',
        );
    }
}
