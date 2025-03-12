<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    protected $fillable = [
        'name',
        'erp_code',
        'type_id',
        'plant',
        'active',
        'history',
        'note'
    ];
    public function type(): BelongsTo
    {
        return $this->belongsTo(DeviceType::class, 'type_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
