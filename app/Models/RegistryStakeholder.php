<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistryStakeholder extends Model
{
    protected $table = 'registry_stakeholders';

    protected $fillable = [
        'registry_id',
        'user_id',
        'stakeholder_type',
    ];

    public function registry(): BelongsTo
    {
        return $this->belongsTo(
            Registry::class,
            'registry_id',
            'id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    public function stakeholderTypeLibrary(): BelongsTo
    {
        return $this->belongsTo(
            StakeholderType::class,
            'stakeholder_type',
            'id'
        );
    }
}
