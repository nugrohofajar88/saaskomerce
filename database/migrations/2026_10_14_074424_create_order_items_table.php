<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Snapshot pattern (sama seperti larashop-be): product_name/sku/variant_label/
     * price/subtotal didenormalisasi di sini. FK product/variant nullOnDelete -
     * riwayat order tetap aman & tampil benar walau produk/varian diedit/dihapus.
     * tenant_id juga disimpan LANGSUNG di sini (bukan cuma lewat relasi ke orders)
     * supaya StockService bisa query product_variants dengan 1 statement yang tetap
     * tenant-scoped tanpa join ke orders.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_name');
            $table->string('product_sku');
            $table->string('variant_label');
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('qty');
            $table->unsignedBigInteger('subtotal');
            // Qty stok yang sudah direservasi dari varian ini - dipakai StockService
            // buat release idempotent (lihat catatan di larashop-be AGENTS.md).
            $table->unsignedInteger('reserved_qty')->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
