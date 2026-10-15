<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\PositionRequest;
use App\Http\Resources\Library\PositionResource;
use App\Managers\PositionManager;
use Illuminate\Http\JsonResponse;

class PositionController extends Controller
{
    public function __construct(private readonly PositionManager $manager) {}

    private function respond(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];
        if ($data !== null) {
            $payload['data'] = $data;
        }
        return response()->json($payload, $status);
    }

    public function positions(PositionRequest $request): JsonResponse
    {
        return $this->respond(
            'Positions retrieved successfully.',
            PositionResource::collection($this->manager->all())->resolve($request)
        );
    }

    public function positionView(PositionRequest $request): JsonResponse
    {
        $model = $this->manager->view((int) $request->validated('id'));
        return $this->respond('Position retrieved successfully.', (new PositionResource($model))->resolve($request));
    }

    public function positionCreate(PositionRequest $request): JsonResponse
    {
        $model = $this->manager->create($request->validated());
        return $this->respond('Position created successfully.', (new PositionResource($model))->resolve($request), 201);
    }

    public function positionUpdate(PositionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $id = (int) $data['id'];
        unset($data['id']);
        $model = $this->manager->update($id, $data);
        return $this->respond('Position updated successfully.', (new PositionResource($model))->resolve($request));
    }

    public function positionDelete(PositionRequest $request): JsonResponse
    {
        $this->manager->delete((int) $request->validated('id'));
        return $this->respond('Position deactivated successfully.');
    }
}
