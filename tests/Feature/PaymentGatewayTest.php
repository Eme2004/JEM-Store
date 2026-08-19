<?php

namespace Tests\Feature;

use App\Services\Payments\BraintreeGatewayService;
use App\Services\Payments\FakeSandboxGatewayService;
use App\Services\Payments\PaymentGatewayContract;
use Tests\TestCase;

class PaymentGatewayTest extends TestCase
{
    public function test_it_binds_the_fake_gateway_when_no_credentials_are_configured(): void
    {
        config([
            'services.braintree.merchant_id' => null,
            'services.braintree.public_key' => null,
            'services.braintree.private_key' => null,
        ]);

        $this->app->forgetInstance(PaymentGatewayContract::class);

        $this->assertInstanceOf(
            FakeSandboxGatewayService::class,
            $this->app->make(PaymentGatewayContract::class)
        );
    }

    public function test_it_binds_the_real_braintree_gateway_once_credentials_are_configured(): void
    {
        config([
            'services.braintree.environment' => 'sandbox',
            'services.braintree.merchant_id' => 'test-merchant',
            'services.braintree.public_key' => 'test-public-key',
            'services.braintree.private_key' => 'test-private-key',
        ]);

        $this->app->forgetInstance(PaymentGatewayContract::class);

        $this->assertInstanceOf(
            BraintreeGatewayService::class,
            $this->app->make(PaymentGatewayContract::class)
        );
    }

    public function test_fake_gateway_approves_the_default_test_nonce(): void
    {
        $gateway = new FakeSandboxGatewayService();

        $result = $gateway->sale('fake-valid-visa-nonce', 10000);

        $this->assertTrue($result->approved);
        $this->assertEquals('sandbox', $result->environment);
        $this->assertNotEmpty($result->transactionId);
        $this->assertEquals('Visa', $result->cardBrand);
        $this->assertEquals('1111', $result->cardLast4);
    }

    public function test_fake_gateway_declines_the_known_decline_nonce(): void
    {
        $gateway = new FakeSandboxGatewayService();

        $result = $gateway->sale('fake-processor-declined-visa-nonce', 10000);

        $this->assertFalse($result->approved);
        $this->assertNotEmpty($result->message);
    }

    public function test_fake_gateway_client_token_never_looks_like_a_real_credential(): void
    {
        $gateway = new FakeSandboxGatewayService();

        $this->assertStringStartsWith('fake-sandbox-client-token-', $gateway->clientToken());
    }
}
