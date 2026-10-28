<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistryTechStack extends Model
{
    protected $table = 'registry_tech_stacks';

    protected $fillable = [
        'registry_id',
        'frontend_stack',
        'backend_stack',
        'database',
        'server_type',
        'server_name',
    ];

    protected $casts = [
        'frontend_stack' => 'array',
        'backend_stack' => 'array',
    ];

    public function registry(): BelongsTo
    {
        return $this->belongsTo(Registry::class, 'registry_id');
    }
}
