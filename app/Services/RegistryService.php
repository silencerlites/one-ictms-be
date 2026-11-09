<?php

namespace App\Services;

use App\Models\Registry;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegistryService
{
    private const RELATIONS = ['registryDesc.managingTypeLibrary', 'registryDesc.managingOfficeDepartment', 'statusLibrary', 'businessOwnerDepartment', 'techStack', 'stakeholders.user', 'stakeholders.stakeholderTypeLibrary', 'docs.documentTypeLibrary', 'repos.developmentSolutionTypeLibrary'];
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

    /**
     * FULLSTACK is exactly two rows (frontend/backend); every other type is one.
     * Preserve the existing `repo` request object so older clients still work.
     */
    private function syncRepositories(Registry $registry, ?array $data): void
    {
        if ($data === null) {
            $registry->repos()->delete();
            return;
        }
        if ($data === []) return;

        $solutionId = $data['development_solution_type'] ?? null;
        if ($solutionId === null) {
            throw ValidationException::withMessages([
                'repo.development_solution_type' => 'Select a Development Solution Type.',
            ]);
        }
        $solutionName = DB::table('development_solution_types')
            ->where('id', $solutionId)->value('name');
        $isFullstack = strtoupper(preg_replace('/[^A-Za-z]/', '', (string) $solutionName)) === 'FULLSTACK';

        if ($isFullstack) {
            $fields = ['repo_git', 'staging_url', 'production_url'];
            $rows = [];
            foreach (['frontend', 'backend'] as $scope) {
                $values = [
                    'development_solution_type' => $solutionId,
                    'repo_scope' => $scope,
                ];
                foreach ($fields as $field) {
                    // Preserve legacy primary repo fields for an old client that has
                    // not yet started supplying dedicated frontend_* properties.
                    $values[$field] = $data[$scope.'_'.$field]
                        ?? ($scope === 'frontend' ? ($data[$field] ?? null) : null);
                }
                $rows[$scope] = $values;
            }
            // Upgrade legacy records previously stored as one `single` row.
            $legacy = $registry->repos()->where('repo_scope', 'single')->first();
            if ($legacy && !$registry->repos()->where('repo_scope', 'frontend')->exists()) {
                $legacy->repo_scope = 'frontend';
                $legacy->save();
            }
            $registry->repos()->whereNotIn('repo_scope', ['frontend', 'backend'])->delete();
            foreach ($rows as $scope => $values) {
                $registry->repos()->updateOrCreate(['repo_scope' => $scope], $values);
            }
            return;
        }

        // Other Development Solution Types must have one repository record.
        $values = [
            'development_solution_type' => $solutionId,
            'repo_scope' => 'single',
            'repo_git' => $data['repo_git'] ?? null,
            'staging_url' => $data['staging_url'] ?? null,
            'production_url' => $data['production_url'] ?? null,
        ];
        $registry->repos()->where('repo_scope', '!=', 'single')->delete();
        $registry->repos()->updateOrCreate(['repo_scope' => 'single'], $values);
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
            $this->syncRepositories($registry, $data['repo']);
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