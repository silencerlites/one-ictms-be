<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentSolutionType extends Model
{
    protected $table = 'development_solution_types';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
