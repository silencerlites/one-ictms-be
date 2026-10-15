<?php

namespace App\Managers;

use App\Models\Division;
use App\Services\DivisionService;
use Illuminate\Database\Eloquent\Collection;

class DivisionManager
{
    public function __construct(private readonly DivisionService $service) {}

    public function all(): Collection { return $this->service->all(); }
    public function view(int $id): Division { return $this->service->view($id); }
    public function create(array $data): Division { return $this->service->create($data); }
    public function update(int $id, array $data): Division { return $this->service->update($id, $data); }
    public function delete(int $id): void { $this->service->delete($id); }
}
