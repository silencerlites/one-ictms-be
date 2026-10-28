<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\ManagingTypeRequest;
use App\Http\Resources\Library\ManagingTypeResource;
use App\Managers\ManagingTypeManager;
use Illuminate\Http\JsonResponse;

class ManagingTypeController extends Controller
{
    public function __construct(private readonly ManagingTypeManager $manager) {}

    private function respond(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];
        if ($data !== null) $payload['data'] = $data;
        return response()->json($payload, $status);
    }

    public function managingTypeList(ManagingTypeRequest $request): JsonResponse
    {
        return $this->respond('ManagingType records retrieved successfully.',
            ManagingTypeResource::collection($this->manager->all())->resolve($request));
    }

    public function managingTypeView(ManagingTypeRequest $request): JsonResponse
    {
        $model = $this->manager->view((int) $request->validated('id'));
        return $this->respond('ManagingType retrieved successfully.', (new ManagingTypeResource($model))->resolve($request));
    }

    public function managingTypeCreate(ManagingTypeRequest $request): JsonResponse
    {
        $model = $this->manager->create($request->validated());
        return $this->respond('ManagingType created successfully.', (new ManagingTypeResource($model))->resolve($request), 201);
    }

    public function managingTypeUpdate(ManagingTypeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $id = (int) $data['id'];
        unset($data['id']);
        $model = $this->manager->update($id, $data);
        return $this->respond('ManagingType updated successfully.', (new ManagingTypeResource($model))->resolve($request));
    }

    public function managingTypeDelete(ManagingTypeRequest $request): JsonResponse
    {
        $this->manager->delete((int) $request->validated('id'));
        return $this->respond('ManagingType deactivated successfully.');
    }
}
