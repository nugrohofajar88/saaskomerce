<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Key-value per tenant. Jangan query model ini langsung dari controller/view -
 * pakai App\Support\SettingService (tenant-aware accessor, lihat kelas itu).
 */
class Setting extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'key',
        'value',
    ];
}
