<?php

namespace App\Support;

use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

/**
 * Cart session-based - port dari larashop-fe GuestCart, tapi re-hydrate harga
 * via Eloquent LANGSUNG (bukan HTTP call ke API terpisah, karena ini monolith)
 * dan session key di-namespace per tenant_id - 1 browser bisa saja buka 2
 * storefront tenant berbeda, jangan sampai cart-nya ketuker/ketimpa.
 *
 * Item disimpan RINGAN (cuma variant_id => qty), harga/nama SELALU dibaca ulang
 * dari DB tiap kali hydrated() dipanggil - gak pernah percaya snapshot basi.
 */
class Cart
{
    private function sessionKey(): string
    {
        return 'cart.tenant.'.TenantContext::require()->id;
    }

    private function raw(): array
    {
        return Session::get($this->sessionKey(), []);
    }

    public function add(int $variantId, int $qty = 1): void
    {
        $items = $this->raw();
        $items[$variantId] = ($items[$variantId] ?? 0) + $qty;
        Session::put($this->sessionKey(), $items);
    }

    public function updateQty(int $variantId, int $qty): void
    {
        $items = $this->raw();

        if ($qty <= 0) {
            unset($items[$variantId]);
        } else {
            $items[$variantId] = $qty;
        }

        Session::put($this->sessionKey(), $items);
    }

    public function remove(int $variantId): void
    {
        $this->updateQty($variantId, 0);
    }

    public function clear(): void
    {
        Session::forget($this->sessionKey());
    }

    /**
     * Lewat hydrated() (bukan cuma hitung raw() session) - item yang stoknya
     * abis sejak ditambahkan ke cart ke-drop di hydrated(), jadi cart yang
     * KELIHATANNYA isi di session tapi semua itemnya udah gak valid lagi
     * tetap dianggap kosong di sini.
     */
    public function isEmpty(): bool
    {
        return $this->hydrated()->isEmpty();
    }

    /**
     * @return Collection<int, array{variant: ProductVariant, qty: int, subtotal: int}>
     */
    public function hydrated(): Collection
    {
        $items = $this->raw();

        if (empty($items)) {
            return collect();
        }

        $variants = ProductVariant::with('product')
            ->whereIn('id', array_keys($items))
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        return collect($items)
            ->map(function ($qty, $variantId) use ($variants) {
                $variant = $variants->get($variantId);

                if (! $variant) {
                    return null; // varian dihapus/nonaktif sejak ditambahkan - drop diam-diam
                }

                $qty = min($qty, $variant->stock); // jangan biarkan qty cart ngelebihin stok terkini

                if ($qty <= 0) {
                    return null; // stok abis sejak ditambahkan ke cart - drop, bukan nampilin baris qty=0
                }

                return [
                    'variant' => $variant,
                    'qty' => $qty,
                    'subtotal' => $variant->price * $qty,
                ];
            })
            ->filter()
            ->values();
    }

    public function total(): int
    {
        return (int) $this->hydrated()->sum('subtotal');
    }
}
