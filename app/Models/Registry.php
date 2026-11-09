<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registry extends Model
{
    protected $table = 'registries';

    protected $fillable = [
        'system_name',
        'alias',
        'description',
        'business_owner',
        'status',
    ];

    public function registryDesc(): HasOne
    {
        return $this->hasOne(RegistryDesc::class, 'registry_id');
    }

    public function techStack(): HasOne
    {
        return $this->hasOne(RegistryTechStack::class, 'registry_id');
    }

    /** Backward-compatible primary repository (single for normal, frontend for FULLSTACK). */
    public function repo(): HasOne
    {
        return $this->hasOne(RegistryRepo::class, 'registry_id')
            ->whereIn('repo_scope', ['single', 'frontend']);
    }

    /** All repository records: one for normal, two for FULLSTACK. */
    public function repos(): HasMany
    {
        return $this->hasMany(RegistryRepo::class, 'registry_id');
    }

    public function stakeholders(): HasMany
    {
        return $this->hasMany(RegistryStakeholder::class, 'registry_id');
    }

    public function docs(): HasMany
    {
        return $this->hasMany(RegistryDoc::class, 'registry_id');
    }

    public function businessOwnerDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'business_owner');
    }

   public function statusLibrary(): BelongsTo
{
    return $this->belongsTo(
        RegistryStatus::class,
        'status',
        'id'
    );
}
}