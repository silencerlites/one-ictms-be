<?php

namespace App\Services;

use App\Models\TechStack;
use Illuminate\Database\Eloquent\Collection;

class TechStackService
{
    public function all(): Collection
    {
        return TechStack::query()->where('is_active', true)->orderBy('name')->get();
    }

    public function view(int $id): TechStack
    {
        return TechStack::query()->findOrFail($id);
    }

    public function create(array $data): TechStack
    {
        return TechStack::query()->create($data);
    }

    public function update(int $id, array $data): TechStack
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
