<?php

namespace App\Managers;

use App\Services\DocumentTypeService;
use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Collection;

class DocumentTypeManager
{
    public function __construct(private readonly DocumentTypeService $service) {}
    public function all(): Collection { return $this->service->all(); }
    public function view(int $id): DocumentType { return $this->service->view($id); }
    public function create(array $data): DocumentType { return $this->service->create($data); }
    public function update(int $id, array $data): DocumentType { return $this->service->update($id, $data); }
    public function delete(int $id): void { $this->service->delete($id); }
}
