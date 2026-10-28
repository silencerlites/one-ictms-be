<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentType extends Model
{
    protected $table = 'development_types';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
