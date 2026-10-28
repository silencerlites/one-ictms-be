<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StakeholderType extends Model
{
    protected $table = 'stakeholder_types';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
