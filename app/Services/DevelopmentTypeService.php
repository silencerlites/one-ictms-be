<?php

namespace App\Services;

use App\Models\DevelopmentType;
use Illuminate\Database\Eloquent\Collection;

class DevelopmentTypeService
{
    public function all(): Collection
    {
        return DevelopmentType::query()->where('is_active', true)->orderBy('name')->get();
    }

    public function view(int $id): DevelopmentType
    {
        return DevelopmentType::query()->findOrFail($id);
    }

    public function create(array $data): DevelopmentType
    {
        return DevelopmentType::query()->create($data);
    }

    public function update(int $id, array $data): DevelopmentType
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
