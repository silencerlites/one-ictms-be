<?php

namespace App\Services;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class DepartmentService
{
    public function all(): Collection
    {
        return Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function view(int $id): Department
    {
        return Department::query()->findOrFail($id);
    }

    public function create(array $data): Department
    {
        $model = Department::query()->create($data);
        return $this->view((int) $model->getKey());
    }

    public function update(int $id, array $data): Department
    {
        $model = Department::query()->findOrFail($id);
        $model->update($data);
        return $this->view($id);
    }

    public function delete(int $id): void
    {
        $model = Department::query()->findOrFail($id);
        if ($model->divisions()->where('is_active', true)->exists()) {
            throw ValidationException::withMessages(['id' => 'Deactivate active divisions before deleting this department.']);
        }
        if ($model->userDetails()->exists()) {
            throw ValidationException::withMessages(['id' => 'This record is assigned to users and cannot be deleted.']);
        }
        // Soft deactivation preserves foreign-key references.
        $model->update(['is_active' => false]);
    }
}
