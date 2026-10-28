<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\TechStackRequest;
use App\Http\Resources\Library\TechStackResource;
use App\Managers\TechStackManager;
use Illuminate\Http\JsonResponse;

class TechStackController extends Controller
{
    public function __construct(private readonly TechStackManager $manager) {}

    private function respond(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];
        if ($data !== null) $payload['data'] = $data;
        return response()->json($payload, $status);
    }

    public function techStackList(TechStackRequest $request): JsonResponse
    {
        return $this->respond('TechStack records retrieved successfully.',
            TechStackResource::collection($this->manager->all())->resolve($request));
    }

    public function techStackView(TechStackRequest $request): JsonResponse
    {
        $model = $this->manager->view((int) $request->validated('id'));
        return $this->respond('TechStack retrieved successfully.', (new TechStackResource($model))->resolve($request));
    }

    public function techStackCreate(TechStackRequest $request): JsonResponse
    {
        $model = $this->manager->create($request->validated());
        return $this->respond('TechStack created successfully.', (new TechStackResource($model))->resolve($request), 201);
    }

    public function techStackUpdate(TechStackRequest $request): JsonResponse
    {
        $data = $request->validated();
        $id = (int) $data['id'];
        unset($data['id']);
        $model = $this->manager->update($id, $data);
        return $this->respond('TechStack updated successfully.', (new TechStackResource($model))->resolve($request));
    }

    public function techStackDelete(TechStackRequest $request): JsonResponse
    {
        $this->manager->delete((int) $request->validated('id'));
        return $this->respond('TechStack deactivated successfully.');
    }
}
