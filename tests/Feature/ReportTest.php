<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(array $data = []): Product
    {
        $ropa = Category::where('slug', 'ropa')->first()
            ?? Category::create(['name' => 'Ropa', 'slug' => 'ropa']);

        $camisas = Category::where('slug', 'camisas')->first()
            ?? Category::create(['name' => 'Camisas', 'slug' => 'camisas', 'parent_id' => $ropa->id]);

        return Product::create(array_merge([
            'category_id' => $camisas->id,
            'name' => 'JEM Test Shirt',
            'slug' => 'jem-test-shirt',
            'description' => 'Producto de prueba para JEM.',
            'price' => 10000,
            'sale_price' => null,
            'stock' => 20,
            'audience' => 'unisex',
            'image' => null,
            'active' => true,
        ], $data));
    }

    private function createOrder(User $user, array $data = []): Order
    {
        static $sequence = 0;
        $sequence++;

        $createdAt = $data['created_at'] ?? null;
        unset($data['created_at']);

        $order = Order::create(array_merge([
            'user_id' => $user->id,
            'order_number' => 'JEM-TEST-'.$sequence,
            'tracking_number' => 'TRK-TEST-'.$sequence,
            'subtotal' => 10000,
            'tax' => 1300,
            'shipping' => 2500,
            'total' => 13800,
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'status' => 'processing',
            'shipping_name' => $user->name,
            'shipping_email' => $user->email,
            'shipping_phone' => '6019-0694',
            'shipping_address' => 'San José, Costa Rica',
        ], $data));

        if ($createdAt) {
            $order->forceFill(['created_at' => $createdAt])->save();
        }

        return $order;
    }

    public function test_reports_require_authentication(): void
    {
        $response = $this->get(route('reports.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_pdf_download_requires_authentication(): void
    {
        $response = $this->get(route('reports.pdf'));

        $response->assertRedirect(route('login'));
    }

    public function test_report_shows_all_confirmed_sales_by_default(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $this->createOrder($userA);
        $this->createOrder($userB);

        $response = $this->actingAs($userA)->get(route('reports.index'));

        $response->assertOk();
        $response->assertViewHas('orders', fn ($orders) => $orders->count() === 2);
        $response->assertViewHas('summary', fn ($summary) => $summary['count'] === 2);
    }

    public function test_report_filters_by_month(): void
    {
        $user = User::factory()->create();

        $inMonth = $this->createOrder($user, [
            'created_at' => '2026-03-15 10:00:00',
        ]);

        $outsideMonth = $this->createOrder($user, [
            'created_at' => '2026-04-10 10:00:00',
        ]);

        $response = $this->actingAs($user)
            ->get(route('reports.index', ['month' => '2026-03']));

        $response->assertOk();
        $response->assertSee($inMonth->order_number);
        $response->assertDontSee($outsideMonth->order_number);
    }

    public function test_report_filters_by_customer(): void
    {
        $userA = User::factory()->create(['name' => 'Cliente A']);
        $userB = User::factory()->create(['name' => 'Cliente B']);

        $orderA = $this->createOrder($userA);
        $orderB = $this->createOrder($userB);

        $response = $this->actingAs($userA)
            ->get(route('reports.index', ['user_id' => $userA->id]));

        $response->assertOk();
        $response->assertSee($orderA->order_number);
        $response->assertDontSee($orderB->order_number);
    }

    public function test_report_excludes_orders_that_are_not_paid(): void
    {
        $user = User::factory()->create();

        $paid = $this->createOrder($user, ['payment_status' => 'paid']);
        $pending = $this->createOrder($user, ['payment_status' => 'pending']);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertSee($paid->order_number);
        $response->assertDontSee($pending->order_number);
    }

    public function test_report_calculates_total_sold(): void
    {
        $user = User::factory()->create();

        $this->createOrder($user, ['total' => 10000]);
        $this->createOrder($user, ['total' => 25000]);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertViewHas('summary', fn ($summary) => (float) $summary['total'] === 35000.0);
    }

    public function test_report_generates_pdf(): void
    {
        $user = User::factory()->create();
        $this->createOrder($user);

        $response = $this->actingAs($user)->get(route('reports.pdf'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_report_generates_pdf_with_filters(): void
    {
        $user = User::factory()->create();
        $this->createOrder($user, ['created_at' => '2026-03-15 10:00:00']);

        $response = $this->actingAs($user)
            ->get(route('reports.pdf', ['month' => '2026-03', 'user_id' => $user->id]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_report_rejects_invalid_month_format(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('reports.index', ['month' => 'not-a-month']));

        $response->assertSessionHasErrors('month');
    }
}
