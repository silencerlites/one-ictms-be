<?php

namespace App\Managers;

use App\Models\Position;
use App\Services\PositionService;
use Illuminate\Database\Eloquent\Collection;

class PositionManager
{
    public function __construct(private readonly PositionService $service) {}

    public function all(): Collection { return $this->service->all(); }
    public function view(int $id): Position { return $this->service->view($id); }
    public function create(array $data): Position { return $this->service->create($data); }
    public function update(int $id, array $data): Position { return $this->service->update($id, $data); }
    public function delete(int $id): void { $this->service->delete($id); }
}
