<?php

namespace App\Managers;

use App\Services\StakeholderTypeService;
use App\Models\StakeholderType;
use Illuminate\Database\Eloquent\Collection;

class StakeholderTypeManager
{
    public function __construct(private readonly StakeholderTypeService $service) {}
    public function all(): Collection { return $this->service->all(); }
    public function view(int $id): StakeholderType { return $this->service->view($id); }
    public function create(array $data): StakeholderType { return $this->service->create($data); }
    public function update(int $id, array $data): StakeholderType { return $this->service->update($id, $data); }
    public function delete(int $id): void { $this->service->delete($id); }
}
