<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistryStatus extends Model
{
    protected $table = 'registry_statuses';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
