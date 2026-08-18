<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'tracking_number',
        'subtotal',
        'tax',
        'shipping',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'shipping' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => match ($this->status) {
            'processing' => 'En preparación',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
            default => $this->status,
        });
    }

    protected function paymentMethodLabel(): Attribute
    {
        return Attribute::get(
            fn () => $this->payment_method === 'paypal' ? 'PayPal' : 'Tarjeta'
        );
    }

    protected function paymentStatusLabel(): Attribute
    {
        return Attribute::get(fn () => match ($this->payment_status) {
            'paid' => 'Pagado',
            'pending' => 'Pendiente',
            'failed' => 'Fallido',
            default => $this->payment_status,
        });
    }
}
