<?php

namespace App\Managers;

use App\Services\TechStackService;
use App\Models\TechStack;
use Illuminate\Database\Eloquent\Collection;

class TechStackManager
{
    public function __construct(private readonly TechStackService $service) {}
    public function all(): Collection { return $this->service->all(); }
    public function view(int $id): TechStack { return $this->service->view($id); }
    public function create(array $data): TechStack { return $this->service->create($data); }
    public function update(int $id, array $data): TechStack { return $this->service->update($id, $data); }
    public function delete(int $id): void { $this->service->delete($id); }
}
