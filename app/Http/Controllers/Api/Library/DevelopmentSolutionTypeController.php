<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\DevelopmentSolutionTypeRequest;
use App\Http\Resources\Library\DevelopmentSolutionTypeResource;
use App\Managers\DevelopmentSolutionTypeManager;
use Illuminate\Http\JsonResponse;

class DevelopmentSolutionTypeController extends Controller
{
    public function __construct(private readonly DevelopmentSolutionTypeManager $manager) {}

    private function respond(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];
        if ($data !== null) $payload['data'] = $data;
        return response()->json($payload, $status);
    }

    public function developmentSolutionTypeList(DevelopmentSolutionTypeRequest $request): JsonResponse
    {
        return $this->respond('DevelopmentSolutionType records retrieved successfully.',
            DevelopmentSolutionTypeResource::collection($this->manager->all())->resolve($request));
    }

    public function developmentSolutionTypeView(DevelopmentSolutionTypeRequest $request): JsonResponse
    {
        $model = $this->manager->view((int) $request->validated('id'));
        return $this->respond('DevelopmentSolutionType retrieved successfully.', (new DevelopmentSolutionTypeResource($model))->resolve($request));
    }

    public function developmentSolutionTypeCreate(DevelopmentSolutionTypeRequest $request): JsonResponse
    {
        $model = $this->manager->create($request->validated());
        return $this->respond('DevelopmentSolutionType created successfully.', (new DevelopmentSolutionTypeResource($model))->resolve($request), 201);
    }

    public function developmentSolutionTypeUpdate(DevelopmentSolutionTypeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $id = (int) $data['id'];
        unset($data['id']);
        $model = $this->manager->update($id, $data);
        return $this->respond('DevelopmentSolutionType updated successfully.', (new DevelopmentSolutionTypeResource($model))->resolve($request));
    }

    public function developmentSolutionTypeDelete(DevelopmentSolutionTypeRequest $request): JsonResponse
    {
        $this->manager->delete((int) $request->validated('id'));
        return $this->respond('DevelopmentSolutionType deactivated successfully.');
    }
}
