<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\DocumentTypeRequest;
use App\Http\Resources\Library\DocumentTypeResource;
use App\Managers\DocumentTypeManager;
use Illuminate\Http\JsonResponse;

class DocumentTypeController extends Controller
{
    public function __construct(private readonly DocumentTypeManager $manager) {}

    private function respond(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];
        if ($data !== null) $payload['data'] = $data;
        return response()->json($payload, $status);
    }

    public function documentTypeList(DocumentTypeRequest $request): JsonResponse
    {
        return $this->respond('DocumentType records retrieved successfully.',
            DocumentTypeResource::collection($this->manager->all())->resolve($request));
    }

    public function documentTypeView(DocumentTypeRequest $request): JsonResponse
    {
        $model = $this->manager->view((int) $request->validated('id'));
        return $this->respond('DocumentType retrieved successfully.', (new DocumentTypeResource($model))->resolve($request));
    }

    public function documentTypeCreate(DocumentTypeRequest $request): JsonResponse
    {
        $model = $this->manager->create($request->validated());
        return $this->respond('DocumentType created successfully.', (new DocumentTypeResource($model))->resolve($request), 201);
    }

    public function documentTypeUpdate(DocumentTypeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $id = (int) $data['id'];
        unset($data['id']);
        $model = $this->manager->update($id, $data);
        return $this->respond('DocumentType updated successfully.', (new DocumentTypeResource($model))->resolve($request));
    }

    public function documentTypeDelete(DocumentTypeRequest $request): JsonResponse
    {
        $this->manager->delete((int) $request->validated('id'));
        return $this->respond('DocumentType deactivated successfully.');
    }
}
