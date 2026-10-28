<?php

namespace App\Services;

use App\Models\ManagingType;
use Illuminate\Database\Eloquent\Collection;

class ManagingTypeService
{
    public function all(): Collection
    {
        return ManagingType::query()->where('is_active', true)->orderBy('name')->get();
    }

    public function view(int $id): ManagingType
    {
        return ManagingType::query()->findOrFail($id);
    }

    public function create(array $data): ManagingType
    {
        return ManagingType::query()->create($data);
    }

    public function update(int $id, array $data): ManagingType
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
