<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechStack extends Model
{
    protected $table = 'tech_stacks';

    protected $fillable = [
        'name',
        'version',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
