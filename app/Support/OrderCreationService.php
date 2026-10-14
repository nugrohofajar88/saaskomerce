<?php

namespace App\Support;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderCreationService
{
    public function __construct(private readonly StockService $stock)
    {
    }

    public function createFromCart(Cart $cart, User $customer, array $customerDetails): Order
    {
        $items = $cart->hydrated();

        return DB::transaction(function () use ($items, $customer, $customerDetails) {
            $total = (int) $items->sum('subtotal');

            $order = Order::create([
                'user_id' => $customer->id,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending_payment',
                'subtotal' => $total,
                'total' => $total,
                'payment_method' => 'transfer',
                'customer_name' => $customerDetails['name'],
                'customer_phone' => $customerDetails['phone'] ?? null,
                'customer_email' => $customer->email,
                'shipping_address' => $customerDetails['address'] ?? null,
            ]);

            foreach ($items as $item) {
                $variant = $item['variant'];

                $order->items()->create([
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'product_sku' => $variant->sku,
                    'variant_label' => $variant->label,
                    'price' => $variant->price,
                    'qty' => $item['qty'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            $this->stock->reserveForOrder($order);

            return $order;
        });
    }

    /** Race-safe: retry kalau tabrakan unique(tenant_id, order_number) - sangat jarang tapi mungkin. */
    private function generateOrderNumber(): string
    {
        return retry(5, function () {
            $candidate = 'ORD-'.now()->format('Ymd').'-'.strtoupper(Str::random(5));

            if (Order::where('order_number', $candidate)->exists()) {
                throw new \RuntimeException('Nomor order bentrok, coba lagi.');
            }

            return $candidate;
        }, 0);
    }
}
