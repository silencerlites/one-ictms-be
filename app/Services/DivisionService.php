<?php

namespace App\Services;

use App\Models\Division;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class DivisionService
{
    public function all(): Collection
    {
        return Division::query()
            ->where('is_active', true)
            ->whereHas('department', fn ($query) => $query->where('is_active', true))
            ->with('department:id,alias,name')
            ->orderBy('name')
            ->get();
    }

    public function view(int $id): Division
    {
        return Division::query()->with('department:id,alias,name')->findOrFail($id);
    }

    public function create(array $data): Division
    {
        $model = Division::query()->create($data);
        return $this->view((int) $model->getKey());
    }

    public function update(int $id, array $data): Division
    {
        $model = Division::query()->findOrFail($id);
        $model->update($data);
        return $this->view($id);
    }

    public function delete(int $id): void
    {
        $model = Division::query()->findOrFail($id);
        if ($model->userDetails()->exists()) {
            throw ValidationException::withMessages(['id' => 'This record is assigned to users and cannot be deleted.']);
        }
        // Soft deactivation preserves foreign-key references.
        $model->update(['is_active' => false]);
    }
}
