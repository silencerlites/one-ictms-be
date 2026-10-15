<?php

namespace App\Managers;

use App\Models\Department;
use App\Services\DepartmentService;
use Illuminate\Database\Eloquent\Collection;

class DepartmentManager
{
    public function __construct(private readonly DepartmentService $service) {}

    public function all(): Collection { return $this->service->all(); }
    public function view(int $id): Department { return $this->service->view($id); }
    public function create(array $data): Department { return $this->service->create($data); }
    public function update(int $id, array $data): Department { return $this->service->update($id, $data); }
    public function delete(int $id): void { $this->service->delete($id); }
}
