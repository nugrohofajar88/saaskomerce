<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Collection;

/**
 * Accessor tenant-aware buat Setting (ganti pola static-global larashop-be).
 * Instance-level cache (BUKAN static/singleton) - sengaja, biar aman kalau
 * suatu saat dipanggil bergantian utk tenant berbeda dalam 1 proses (mis. command
 * cron yang loop semua tenant). Bikin instance baru per tenant yang mau dibaca:
 * `new SettingService($tenantId)` atau `new SettingService()` buat tenant aktif.
 */
class SettingService
{
    private ?Collection $cache = null;

    public function __construct(private readonly ?int $tenantId = null)
    {
    }

    private function tenantId(): int
    {
        return $this->tenantId ?? TenantContext::require()->id;
    }

    private function all(): Collection
    {
        if ($this->cache === null) {
            $this->cache = Setting::withoutTenantScope()
                ->where('tenant_id', $this->tenantId())
                ->pluck('value', 'key');
        }

        return $this->cache;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()->get($key, $default);
    }

    public function set(string $key, mixed $value): void
    {
        Setting::withoutTenantScope()->updateOrCreate(
            ['tenant_id' => $this->tenantId(), 'key' => $key],
            ['value' => $value],
        );

        $this->cache = null; // invalidate, re-load on next read
    }

    public function bool(string $key, bool $default = false): bool
    {
        $value = $this->get($key);

        return $value === null ? $default : filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function storeOpen(): bool
    {
        return $this->bool('store_open', true);
    }

    public function paymentTransferEnabled(): bool
    {
        return $this->bool('payment_transfer_enabled', true);
    }
}
