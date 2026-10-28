<?php

namespace App\Services;

use App\Models\StakeholderType;
use Illuminate\Database\Eloquent\Collection;

class StakeholderTypeService
{
    public function all(): Collection
    {
        return StakeholderType::query()->where('is_active', true)->orderBy('name')->get();
    }

    public function view(int $id): StakeholderType
    {
        return StakeholderType::query()->findOrFail($id);
    }

    public function create(array $data): StakeholderType
    {
        return StakeholderType::query()->create($data);
    }

    public function update(int $id, array $data): StakeholderType
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
