<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\DevelopmentTypeRequest;
use App\Http\Resources\Library\DevelopmentTypeResource;
use App\Managers\DevelopmentTypeManager;
use Illuminate\Http\JsonResponse;

class DevelopmentTypeController extends Controller
{
    public function __construct(private readonly DevelopmentTypeManager $manager) {}

    private function respond(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];
        if ($data !== null) $payload['data'] = $data;
        return response()->json($payload, $status);
    }

    public function developmentTypeList(DevelopmentTypeRequest $request): JsonResponse
    {
        return $this->respond('DevelopmentType records retrieved successfully.',
            DevelopmentTypeResource::collection($this->manager->all())->resolve($request));
    }

    public function developmentTypeView(DevelopmentTypeRequest $request): JsonResponse
    {
        $model = $this->manager->view((int) $request->validated('id'));
        return $this->respond('DevelopmentType retrieved successfully.', (new DevelopmentTypeResource($model))->resolve($request));
    }

    public function developmentTypeCreate(DevelopmentTypeRequest $request): JsonResponse
    {
        $model = $this->manager->create($request->validated());
        return $this->respond('DevelopmentType created successfully.', (new DevelopmentTypeResource($model))->resolve($request), 201);
    }

    public function developmentTypeUpdate(DevelopmentTypeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $id = (int) $data['id'];
        unset($data['id']);
        $model = $this->manager->update($id, $data);
        return $this->respond('DevelopmentType updated successfully.', (new DevelopmentTypeResource($model))->resolve($request));
    }

    public function developmentTypeDelete(DevelopmentTypeRequest $request): JsonResponse
    {
        $this->manager->delete((int) $request->validated('id'));
        return $this->respond('DevelopmentType deactivated successfully.');
    }
}
