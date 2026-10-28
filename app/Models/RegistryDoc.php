<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistryDoc extends Model
{
    protected $table = 'registry_docs';

    protected $fillable = [
        'registry_id',
        'docs_type',
        'file_path',
    ];

    public function registry(): BelongsTo
    {
        return $this->belongsTo(
            Registry::class,
            'registry_id',
            'id'
        );
    }

    public function documentTypeLibrary(): BelongsTo
    {
        return $this->belongsTo(
            DocumentType::class,
            'docs_type',
            'id'
        );
    }
}
