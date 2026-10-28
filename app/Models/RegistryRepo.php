<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistryRepo extends Model
{
    protected $table = 'registry_repos';

    protected $fillable = [
        'registry_id',
        'staging_url',
        'production_url',
        'repo_git',
        'development_solution_type',
    ];

    public function registry(): BelongsTo
    {
        return $this->belongsTo(
            Registry::class,
            'registry_id',
            'id'
        );
    }

    public function developmentSolutionTypeLibrary(): BelongsTo
    {
        return $this->belongsTo(
            DevelopmentSolutionType::class,
            'development_solution_type',
            'id'
        );
    }
}
