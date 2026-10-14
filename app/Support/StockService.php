<?php

namespace App\Support;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Validation\ValidationException;

/**
 * Reserve/release stok atomik - mekanisme di-port dari larashop-be StockService.
 * Sengaja TIDAK bergantung pada tenant context ambient (pakai withoutTenantScope()
 * + tenant_id eksplisit dari $order di tiap query) - biar aman dipanggil baik dari
 * request HTTP (tenant ter-bind) MAUPUN dari command cron lintas-tenant
 * (orders:expire-unpaid, tidak ada tenant context sama sekali).
 *
 * PENTING: query item LANGSUNG via OrderItem::withoutTenantScope() (bukan lewat
 * relasi $order->items) - relasi tetap kena global scope OrderItem sendiri walau
 * $order-nya didapat dari Order::withoutTenantScope(), jadi tetap throw kalau
 * dipanggil tanpa tenant context (mis. dari command cron lintas-tenant).
 */
class StockService
{
    /** Panggil di dalam DB::transaction() milik caller. Throw kalau stok kurang. */
    public function reserveForOrder(Order $order): void
    {
        $items = OrderItem::withoutTenantScope()->where('order_id', $order->id)->get();

        foreach ($items as $item) {
            if (! $item->product_variant_id) {
                continue; // varian sudah dihapus permanen - gak ada yg bisa direservasi
            }

            $affected = ProductVariant::withoutTenantScope()
                ->where('id', $item->product_variant_id)
                ->where('tenant_id', $order->tenant_id)
                ->where('stock', '>=', $item->qty)
                ->decrement('stock', $item->qty);

            if ($affected === 0) {
                throw ValidationException::withMessages([
                    'items' => "Stok \"{$item->product_name} ({$item->variant_label})\" tidak cukup.",
                ]);
            }

            $item->update(['reserved_qty' => $item->qty]);
        }
    }

    /** Idempotent - cuma release item yang masih punya reserved_qty > 0. */
    public function releaseForOrder(Order $order): void
    {
        $items = OrderItem::withoutTenantScope()->where('order_id', $order->id)->where('reserved_qty', '>', 0)->get();

        foreach ($items as $item) {
            if (! $item->product_variant_id) {
                continue;
            }

            ProductVariant::withoutTenantScope()
                ->where('id', $item->product_variant_id)
                ->where('tenant_id', $order->tenant_id)
                ->increment('stock', $item->reserved_qty);

            $item->update(['reserved_qty' => 0]);
        }
    }
}
