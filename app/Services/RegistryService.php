<?php

namespace App\Services;

use App\Models\Registry;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegistryService
{
    private const RELATIONS = ['registryDesc.managingTypeLibrary', 'registryDesc.managingOfficeDepartment', 'statusLibrary', 'businessOwnerDepartment', 'techStack', 'stakeholders.user', 'stakeholders.stakeholderTypeLibrary', 'docs.documentTypeLibrary', 'repo.developmentSolutionTypeLibrary'];
    private const RELATED = ['desc', 'tech_stack', 'stakeholders', 'docs', 'repo'];

    public function all(): Collection
    {
        return Registry::with(self::RELATIONS)->orderByDesc('id')->get();
    }

    public function view(int $id): Registry
    {
        return Registry::with(self::RELATIONS)->findOrFail($id);
    }

    public function create(array $data): Registry
    {
        return DB::transaction(function () use ($data): Registry {
            $registry = Registry::create(Arr::except($data, self::RELATED));
            $this->syncRelations($registry, $data);
            return $this->view((int) $registry->getKey());
        });
    }

    public function update(int $id, array $data): Registry
    {
        return DB::transaction(function () use ($id, $data): Registry {
            $registry = Registry::findOrFail($id);
            $registry->update(Arr::except($data, array_merge(['id'], self::RELATED)));
            $this->syncRelations($registry, $data);
            return $this->view($id);
        });
    }

    public function delete(int $id): void
    {
        DB::transaction(static function () use ($id): void {
            // Existing foreign keys cascade-delete tech stack, docs, and repo.
            // Stakeholders are also cascaded, but actual users are not deleted.
            Registry::findOrFail($id)->delete();
        });
    }

    private function syncRelations(Registry $registry, array $data): void
    {
        if (array_key_exists('desc', $data)) {
            if ($data['desc'] === null) $registry->registryDesc()->delete();
            elseif ($data['desc'] !== []) $registry->registryDesc()->updateOrCreate([], $data['desc']);
        }

        if (array_key_exists('tech_stack', $data)) {
            if ($data['tech_stack'] === null) {
                $registry->techStack()->delete();
            } elseif ($data['tech_stack'] !== []) {
                $registry->techStack()->updateOrCreate([], $data['tech_stack']);
            }
        }

        if (array_key_exists('repo', $data)) {
            if ($data['repo'] === null) {
                $registry->repo()->delete();
            } elseif ($data['repo'] !== []) {
                
                $registry->repo()->updateOrCreate([], $data['repo']);
            }
        }

        if (array_key_exists('stakeholders', $data)) {
            $stakeholders = $data['stakeholders'];
            $unique = [];
            foreach ($stakeholders as $index => $item) {
                $key = $item['user_id'].'|'.$item['stakeholder_type'];
                if (isset($unique[$key])) {
                    throw ValidationException::withMessages([
                        "stakeholders.$index" => 'Duplicate user and stakeholder type.',
                    ]);
                }
                $unique[$key] = true;
            }
            $registry->stakeholders()->delete();
            $registry->stakeholders()->createMany($stakeholders);
        }

        if (array_key_exists('docs', $data)) {
            // file_path values are metadata; upload handling belongs to a separate secure upload endpoint.
            $registry->docs()->delete();
            $registry->docs()->createMany($data['docs']);
        }
    }
}