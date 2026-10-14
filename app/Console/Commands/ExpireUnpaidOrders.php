<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Support\StockService;
use Illuminate\Console\Command;

/**
 * Lintas-tenant dengan sengaja (withoutTenantScope) - cron ini jalan 1x per
 * proses buat SEMUA tenant, bukan di-loop per tenant. Order pending_payment
 * lebih dari 24 jam dibatalkan otomatis & stoknya dikembalikan.
 */
class ExpireUnpaidOrders extends Command
{
    protected $signature = 'orders:expire-unpaid {--hours=24}';

    protected $description = 'Batalkan order pending_payment yang sudah lewat batas waktu & kembalikan stoknya.';

    public function handle(StockService $stock): int
    {
        $cutoff = now()->subHours((int) $this->option('hours'));

        // TIDAK eager-load 'items' - relasi itu tetap kena global scope OrderItem
        // sendiri (lintas-tenant, gak ada tenant context di command ini).
        // StockService::releaseForOrder() query item-nya sendiri via
        // OrderItem::withoutTenantScope().
        $orders = Order::withoutTenantScope()
            ->where('status', 'pending_payment')
            ->where('created_at', '<', $cutoff)
            ->get();

        foreach ($orders as $order) {
            $stock->releaseForOrder($order);
            $order->update(['status' => 'expired']);
            $this->line("Expired: {$order->order_number} (tenant #{$order->tenant_id})");
        }

        $this->info("Selesai - {$orders->count()} order di-expire.");

        return self::SUCCESS;
    }
}
