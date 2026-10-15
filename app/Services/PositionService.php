<?php

namespace App\Services;

use App\Models\Position;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class PositionService
{
    public function all(): Collection
    {
        return Position::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function view(int $id): Position
    {
        return Position::query()->findOrFail($id);
    }

    public function create(array $data): Position
    {
        $model = Position::query()->create($data);
        return $this->view((int) $model->getKey());
    }

    public function update(int $id, array $data): Position
    {
        $model = Position::query()->findOrFail($id);
        $model->update($data);
        return $this->view($id);
    }

    public function delete(int $id): void
    {
        $model = Position::query()->findOrFail($id);
        if ($model->userDetails()->exists()) {
            throw ValidationException::withMessages(['id' => 'This record is assigned to users and cannot be deleted.']);
        }
        // Soft deactivation preserves foreign-key references.
        $model->update(['is_active' => false]);
    }
}
