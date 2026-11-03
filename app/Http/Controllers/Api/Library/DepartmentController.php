<?php

namespace App\Http\Controllers\Api\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\DepartmentRequest;
use App\Http\Resources\Library\DepartmentResource;
use App\Managers\DepartmentManager;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    public function __construct(private readonly DepartmentManager $manager) {}

    private function respond(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];
        if ($data !== null) {
            $payload['data'] = $data;
        }
        return response()->json($payload, $status);
    }

    public function departments(DepartmentRequest $request): JsonResponse
    {
        return $this->respond(
            'Departments retrieved successfully.',
            DepartmentResource::collection($this->manager->all())->resolve($request)
        );
    }

    public function departmentUsers(DepartmentRequest $request): JsonResponse
    {
        $departmentId = (int) $request->validated('department_id');
        $users = $this->manager->users($departmentId);

        $data = $users->map(static function ($user) use ($departmentId): array {
            // A user's display name may be stored in users.name or user_details.
            $name = trim((string) ($user->name ?? ''));
            if ($name === '') {
                $name = trim(implode(' ', array_filter([
                    data_get($user, 'details.first_name'),
                    data_get($user, 'details.middle_name'),
                    data_get($user, 'details.last_name'),
                ], static fn ($part): bool => is_string($part) && trim($part) !== '')));
            }

            return [
                'id' => (int) $user->getKey(),
                'name' => $name !== '' ? $name : 'User #'.$user->getKey(),
                'email' => $user->email,
                'department_id' => $departmentId,
            ];
        })->values()->all();

        return $this->respond('Department users retrieved successfully.', $data);
    }

    public function departmentView(DepartmentRequest $request): JsonResponse
    {
        $model = $this->manager->view((int) $request->validated('id'));
        return $this->respond('Department retrieved successfully.', (new DepartmentResource($model))->resolve($request));
    }

    public function departmentCreate(DepartmentRequest $request): JsonResponse
    {
        $model = $this->manager->create($request->validated());
        return $this->respond('Department created successfully.', (new DepartmentResource($model))->resolve($request), 201);
    }

    public function departmentUpdate(DepartmentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $id = (int) $data['id'];
        unset($data['id']);
        $model = $this->manager->update($id, $data);
        return $this->respond('Department updated successfully.', (new DepartmentResource($model))->resolve($request));
    }

    public function departmentDelete(DepartmentRequest $request): JsonResponse
    {
        $this->manager->delete((int) $request->validated('id'));
        return $this->respond('Department deactivated successfully.');
    }
}
