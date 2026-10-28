<?php

namespace App\Managers;

use App\Services\DevelopmentTypeService;
use App\Models\DevelopmentType;
use Illuminate\Database\Eloquent\Collection;

class DevelopmentTypeManager
{
    public function __construct(private readonly DevelopmentTypeService $service) {}
    public function all(): Collection { return $this->service->all(); }
    public function view(int $id): DevelopmentType { return $this->service->view($id); }
    public function create(array $data): DevelopmentType { return $this->service->create($data); }
    public function update(int $id, array $data): DevelopmentType { return $this->service->update($id, $data); }
    public function delete(int $id): void { $this->service->delete($id); }
}
