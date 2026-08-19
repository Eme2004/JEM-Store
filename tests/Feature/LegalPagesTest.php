<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_disclaimer_page_is_public(): void
    {
        $response = $this->get(route('legal.disclaimer'));

        $response->assertOk();
        $response->assertSee('Disclaimer');
    }

    public function test_terms_page_is_public(): void
    {
        $response = $this->get(route('legal.terms'));

        $response->assertOk();
        $response->assertSee('Terms of Use');
    }

    public function test_privacy_page_is_public(): void
    {
        $response = $this->get(route('legal.privacy'));

        $response->assertOk();
        $response->assertSee('Privacy Policy');
    }

    public function test_footer_links_to_all_three_legal_pages(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee(route('legal.disclaimer'), false);
        $response->assertSee(route('legal.terms'), false);
        $response->assertSee(route('legal.privacy'), false);
    }
}
