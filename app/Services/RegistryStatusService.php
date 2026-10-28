<?php

namespace App\Services;

use App\Models\RegistryStatus;
use Illuminate\Database\Eloquent\Collection;

class RegistryStatusService
{
    public function all(): Collection
    {
        return RegistryStatus::query()->where('is_active', true)->orderBy('name')->get();
    }

    public function view(int $id): RegistryStatus
    {
        return RegistryStatus::query()->findOrFail($id);
    }

    public function create(array $data): RegistryStatus
    {
        return RegistryStatus::query()->create($data);
    }

    public function update(int $id, array $data): RegistryStatus
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
