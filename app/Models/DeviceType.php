<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeviceType extends Model
{
    protected $fillable = ['name', 'note'];

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'type_id');
    }
}
