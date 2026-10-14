<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            // nullOnDelete (bukan cascade) - order harus tetap ada di riwayat/laporan
            // tenant walau akun customer-nya suatu saat dihapus.
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('order_number');
            $table->string('status')->default('pending_payment'); // pending_payment|paid|cancelled|expired
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('total');
            $table->string('payment_method')->default('transfer');
            // Snapshot penerima - sama pola snapshot order_items, aman walau data customer berubah.
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('payment_proof_path')->nullable();
            $table->timestamp('payment_proof_uploaded_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'order_number']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
