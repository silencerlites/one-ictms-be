<?php

namespace App\Managers;

use App\Services\ManagingTypeService;
use App\Models\ManagingType;
use Illuminate\Database\Eloquent\Collection;

class ManagingTypeManager
{
    public function __construct(private readonly ManagingTypeService $service) {}
    public function all(): Collection { return $this->service->all(); }
    public function view(int $id): ManagingType { return $this->service->view($id); }
    public function create(array $data): ManagingType { return $this->service->create($data); }
    public function update(int $id, array $data): ManagingType { return $this->service->update($id, $data); }
    public function delete(int $id): void { $this->service->delete($id); }
}
