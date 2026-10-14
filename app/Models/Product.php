<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Varian = sumber kebenaran (pola di-port dari larashop-be) - product TIDAK
 * punya kolom price/stock/weight. Harga/stok/berat diturunkan dari
 * defaultVariant() / di-SUM dari semua varian aktif. Jangan tambah kolom
 * price/stock di sini - itu bakal bikin state ganda yang bisa divergen.
 */
class Product extends Model
{
    use BelongsToTenant, HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function defaultVariant(): ?ProductVariant
    {
        $variants = $this->relationLoaded('variants') ? $this->variants : $this->variants()->get();

        return $variants->firstWhere('is_default', true) ?? $variants->first();
    }

    public function getPriceAttribute(): ?int
    {
        return $this->defaultVariant()?->price;
    }

    public function getWeightGramsAttribute(): ?int
    {
        return $this->defaultVariant()?->weight_grams;
    }

    /** SUM stok semua varian AKTIF (bukan cuma varian default). */
    public function getStockAttribute(): int
    {
        $variants = $this->relationLoaded('variants') ? $this->variants : $this->variants()->get();

        return (int) $variants->where('is_active', true)->sum('stock');
    }

    /** Produk dianggap "tersedia" kalau minimal 1 varian aktif punya stok > 0. */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->whereHas('variants', fn (Builder $q) => $q->where('is_active', true)->where('stock', '>', 0));
    }
}
