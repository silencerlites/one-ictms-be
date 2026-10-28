<?php

namespace App\Managers;

use App\Models\Registry;
use App\Services\RegistryService;
use Illuminate\Database\Eloquent\Collection;

class RegistryManager
{
    public function __construct(private readonly RegistryService $service) {}

    public function all(): Collection { return $this->service->all(); }
    public function view(int $id): Registry { return $this->service->view($id); }
    public function create(array $data): Registry { return $this->service->create($data); }
    public function update(int $id, array $data): Registry { return $this->service->update($id, $data); }
    public function delete(int $id): void { $this->service->delete($id); }
}
