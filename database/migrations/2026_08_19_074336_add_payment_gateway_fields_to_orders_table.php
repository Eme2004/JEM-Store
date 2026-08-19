<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_gateway')->nullable()->after('payment_method');
            $table->string('payment_environment')->nullable()->after('payment_gateway');
            $table->string('transaction_id')->nullable()->after('payment_status');
            $table->string('card_brand')->nullable()->after('transaction_id');
            $table->string('card_last4', 4)->nullable()->after('card_brand');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_gateway',
                'payment_environment',
                'transaction_id',
                'card_brand',
                'card_last4',
            ]);
        });
    }
};
