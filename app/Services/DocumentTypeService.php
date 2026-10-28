<?php

namespace App\Services;

use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Collection;

class DocumentTypeService
{
    public function all(): Collection
    {
        return DocumentType::query()->where('is_active', true)->orderBy('name')->get();
    }

    public function view(int $id): DocumentType
    {
        return DocumentType::query()->findOrFail($id);
    }

    public function create(array $data): DocumentType
    {
        return DocumentType::query()->create($data);
    }

    public function update(int $id, array $data): DocumentType
    {
        $model = $this->view($id);
        $model->update($data);
        return $model->refresh();
    }

    public function delete(int $id): void
    {
        // Preserve references from registry and historical records.
        $this->view($id)->update(['is_active' => false]);
    }
}
