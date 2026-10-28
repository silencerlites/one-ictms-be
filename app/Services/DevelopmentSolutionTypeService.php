<?php

namespace App\Services;

use App\Models\DevelopmentSolutionType;
use Illuminate\Database\Eloquent\Collection;

class DevelopmentSolutionTypeService
{
    public function all(): Collection
    {
        return DevelopmentSolutionType::query()->where('is_active', true)->orderBy('name')->get();
    }

    public function view(int $id): DevelopmentSolutionType
    {
        return DevelopmentSolutionType::query()->findOrFail($id);
    }

    public function create(array $data): DevelopmentSolutionType
    {
        return DevelopmentSolutionType::query()->create($data);
    }

    public function update(int $id, array $data): DevelopmentSolutionType
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
