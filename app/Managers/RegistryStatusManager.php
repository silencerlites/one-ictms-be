<?php

namespace App\Managers;

use App\Services\RegistryStatusService;
use App\Models\RegistryStatus;
use Illuminate\Database\Eloquent\Collection;

class RegistryStatusManager
{
    public function __construct(private readonly RegistryStatusService $service) {}
    public function all(): Collection { return $this->service->all(); }
    public function view(int $id): RegistryStatus { return $this->service->view($id); }
    public function create(array $data): RegistryStatus { return $this->service->create($data); }
    public function update(int $id, array $data): RegistryStatus { return $this->service->update($id, $data); }
    public function delete(int $id): void { $this->service->delete($id); }
}
