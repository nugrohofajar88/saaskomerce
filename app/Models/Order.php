<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use BelongsToTenant, HasFactory;

    // status: pending_payment | paid | cancelled | expired
    protected $fillable = [
        'tenant_id',
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'total',
        'payment_method',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'payment_proof_path',
        'payment_proof_uploaded_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'payment_proof_uploaded_at' => 'datetime',
            'paid_at' => 'datetime',
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
}
