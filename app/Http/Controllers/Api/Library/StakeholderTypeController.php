<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\StakeholderTypeRequest;
use App\Http\Resources\Library\StakeholderTypeResource;
use App\Managers\StakeholderTypeManager;
use Illuminate\Http\JsonResponse;

class StakeholderTypeController extends Controller
{
    public function __construct(private readonly StakeholderTypeManager $manager) {}

    private function respond(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];
        if ($data !== null) $payload['data'] = $data;
        return response()->json($payload, $status);
    }

    public function stakeholderTypeList(StakeholderTypeRequest $request): JsonResponse
    {
        return $this->respond('StakeholderType records retrieved successfully.',
            StakeholderTypeResource::collection($this->manager->all())->resolve($request));
    }

    public function stakeholderTypeView(StakeholderTypeRequest $request): JsonResponse
    {
        $model = $this->manager->view((int) $request->validated('id'));
        return $this->respond('StakeholderType retrieved successfully.', (new StakeholderTypeResource($model))->resolve($request));
    }

    public function stakeholderTypeCreate(StakeholderTypeRequest $request): JsonResponse
    {
        $model = $this->manager->create($request->validated());
        return $this->respond('StakeholderType created successfully.', (new StakeholderTypeResource($model))->resolve($request), 201);
    }

    public function stakeholderTypeUpdate(StakeholderTypeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $id = (int) $data['id'];
        unset($data['id']);
        $model = $this->manager->update($id, $data);
        return $this->respond('StakeholderType updated successfully.', (new StakeholderTypeResource($model))->resolve($request));
    }

    public function stakeholderTypeDelete(StakeholderTypeRequest $request): JsonResponse
    {
        $this->manager->delete((int) $request->validated('id'));
        return $this->respond('StakeholderType deactivated successfully.');
    }
}
