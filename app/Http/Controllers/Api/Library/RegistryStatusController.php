<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\RegistryStatusRequest;
use App\Http\Resources\Library\RegistryStatusResource;
use App\Managers\RegistryStatusManager;
use Illuminate\Http\JsonResponse;

class RegistryStatusController extends Controller
{
    public function __construct(private readonly RegistryStatusManager $manager) {}

    private function respond(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];
        if ($data !== null) $payload['data'] = $data;
        return response()->json($payload, $status);
    }

    public function registryStatusList(RegistryStatusRequest $request): JsonResponse
    {
        return $this->respond('RegistryStatus records retrieved successfully.',
            RegistryStatusResource::collection($this->manager->all())->resolve($request));
    }

    public function registryStatusView(RegistryStatusRequest $request): JsonResponse
    {
        $model = $this->manager->view((int) $request->validated('id'));
        return $this->respond('RegistryStatus retrieved successfully.', (new RegistryStatusResource($model))->resolve($request));
    }

    public function registryStatusCreate(RegistryStatusRequest $request): JsonResponse
    {
        $model = $this->manager->create($request->validated());
        return $this->respond('RegistryStatus created successfully.', (new RegistryStatusResource($model))->resolve($request), 201);
    }

    public function registryStatusUpdate(RegistryStatusRequest $request): JsonResponse
    {
        $data = $request->validated();
        $id = (int) $data['id'];
        unset($data['id']);
        $model = $this->manager->update($id, $data);
        return $this->respond('RegistryStatus updated successfully.', (new RegistryStatusResource($model))->resolve($request));
    }

    public function registryStatusDelete(RegistryStatusRequest $request): JsonResponse
    {
        $this->manager->delete((int) $request->validated('id'));
        return $this->respond('RegistryStatus deactivated successfully.');
    }
}
