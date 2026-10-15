<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\DivisionRequest;
use App\Http\Resources\Library\DivisionResource;
use App\Managers\DivisionManager;
use Illuminate\Http\JsonResponse;

class DivisionController extends Controller
{
    public function __construct(private readonly DivisionManager $manager) {}

    private function respond(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];
        if ($data !== null) {
            $payload['data'] = $data;
        }
        return response()->json($payload, $status);
    }

    public function divisions(DivisionRequest $request): JsonResponse
    {
        return $this->respond(
            'Divisions retrieved successfully.',
            DivisionResource::collection($this->manager->all())->resolve($request)
        );
    }

    public function divisionView(DivisionRequest $request): JsonResponse
    {
        $model = $this->manager->view((int) $request->validated('id'));
        return $this->respond('Division retrieved successfully.', (new DivisionResource($model))->resolve($request));
    }

    public function divisionCreate(DivisionRequest $request): JsonResponse
    {
        $model = $this->manager->create($request->validated());
        return $this->respond('Division created successfully.', (new DivisionResource($model))->resolve($request), 201);
    }

    public function divisionUpdate(DivisionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $id = (int) $data['id'];
        unset($data['id']);
        $model = $this->manager->update($id, $data);
        return $this->respond('Division updated successfully.', (new DivisionResource($model))->resolve($request));
    }

    public function divisionDelete(DivisionRequest $request): JsonResponse
    {
        $this->manager->delete((int) $request->validated('id'));
        return $this->respond('Division deactivated successfully.');
    }
}
