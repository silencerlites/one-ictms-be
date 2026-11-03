<?php

namespace App\Services;

use App\Models\Department;
use App\Models\User;
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

    public function users(int $departmentId): Collection
    {
        // Uses User::details() -> user_details.department_id.
        // A user is returned once even if several detail records match.
        return User::query()
            ->with('details')
            ->whereHas('details', static function ($query) use ($departmentId): void {
                $query->where('department_id', $departmentId);
            })
            ->orderBy('id')
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
        $model->update(['is_active' => false]);
    }
}
