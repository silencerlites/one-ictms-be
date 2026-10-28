<?php

namespace App\Managers;

use App\Services\DevelopmentSolutionTypeService;
use App\Models\DevelopmentSolutionType;
use Illuminate\Database\Eloquent\Collection;

class DevelopmentSolutionTypeManager
{
    public function __construct(private readonly DevelopmentSolutionTypeService $service) {}
    public function all(): Collection { return $this->service->all(); }
    public function view(int $id): DevelopmentSolutionType { return $this->service->view($id); }
    public function create(array $data): DevelopmentSolutionType { return $this->service->create($data); }
    public function update(int $id, array $data): DevelopmentSolutionType { return $this->service->update($id, $data); }
    public function delete(int $id): void { $this->service->delete($id); }
}
