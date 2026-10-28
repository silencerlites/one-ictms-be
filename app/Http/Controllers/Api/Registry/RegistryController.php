<?php

namespace App\Http\Controllers\Api\Registry;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\RegistryRequest;
use App\Http\Resources\Registry\RegistryResource;
use App\Managers\RegistryManager;
use Illuminate\Http\JsonResponse;

class RegistryController extends Controller
{
    public function __construct(private readonly RegistryManager $manager) {}

    private function respond(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];
        if ($data !== null) {
            $payload['data'] = $data;
        }
        return response()->json($payload, $status);
    }

    public function index(RegistryRequest $request): JsonResponse
    {
        return $this->respond('Registries retrieved successfully.',
            RegistryResource::collection($this->manager->all())->resolve($request));
    }

    public function view(RegistryRequest $request): JsonResponse
    {
        return $this->respond('Registry retrieved successfully.',
            (new RegistryResource($this->manager->view((int) $request->validated('id'))))->resolve($request));
    }

    public function create(RegistryRequest $request): JsonResponse
    {
        return $this->respond('Registry created successfully.',
            (new RegistryResource($this->manager->create($request->validated())))->resolve($request), 201);
    }

    public function update(RegistryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $id = (int) $data['id'];
        unset($data['id']);
        return $this->respond('Registry updated successfully.',
            (new RegistryResource($this->manager->update($id, $data)))->resolve($request));
    }

    public function delete(RegistryRequest $request): JsonResponse
    {
        $this->manager->delete((int) $request->validated('id'));
        return $this->respond('Registry deleted successfully.');
    }
}
