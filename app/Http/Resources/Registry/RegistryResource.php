<?php

namespace App\Http\Resources\Registry;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'system_name' => $this->system_name,
            'alias' => $this->alias,
            'description' => $this->description,
            'business_owner' => $this->business_owner,
            'business_owner_department' => $this->whenLoaded('businessOwnerDepartment', fn () => $this->businessOwnerDepartment ? [
                'id' => $this->businessOwnerDepartment->id,
                'alias' => $this->businessOwnerDepartment->alias,
                'name' => $this->businessOwnerDepartment->name,
            ] : null),
            'status' => $this->status,
            'status_name' => $this->whenLoaded('statusLibrary', fn () => $this->statusLibrary?->name),
            'desc' => $this->whenLoaded('registryDesc', function () {
                $desc = $this->registryDesc;
                return $desc ? [
                    'id' => $desc->id,
                    'registry_id' => $desc->registry_id,
                    'managing_type' => $desc->managing_type,
                    'managing_type_name' => $desc->managingTypeLibrary?->name,
                    'managing_office' => $desc->managing_office,
                    'managing_office_department' => $desc->managingOfficeDepartment ? [
                        'id' => $desc->managingOfficeDepartment->id,
                        'alias' => $desc->managingOfficeDepartment->alias,
                        'name' => $desc->managingOfficeDepartment->name,
                    ] : null,
                    'third_party_company' => $desc->third_party_company,
                    'project_roll_on' => $desc->project_roll_on?->format('Y-m-d'),
                    'project_sign_off' => $desc->project_sign_off?->format('Y-m-d'),
                    'estimation_project_duration' => $desc->estimation_project_duration,
                ] : null;
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tech_stack' => $this->whenLoaded('techStack', function () {
                $stack = $this->techStack;
                return $stack ? [
                    'id' => $stack->id,
                    'registry_id' => $stack->registry_id,
                    'frontend_stack' => $stack->frontend_stack,
                    'backend_stack' => $stack->backend_stack,
                    'database' => $stack->database,
                    'server_type' => $stack->server_type,
                    'server_name' => $stack->server_name,
                    'created_at' => $stack->created_at,
                    'updated_at' => $stack->updated_at,
                ] : null;
            }),
            'stakeholders' => $this->whenLoaded('stakeholders', function () {
                return $this->stakeholders->map(fn ($stakeholder) => [
                    'id' => $stakeholder->id,
                    'registry_id' => $stakeholder->registry_id,
                    'user_id' => $stakeholder->user_id,
                    'stakeholder_type' => $stakeholder->stakeholder_type,
                    'stakeholder_type_name' => $stakeholder->stakeholderTypeLibrary?->name,
                    'created_at' => $stakeholder->created_at,
                    'updated_at' => $stakeholder->updated_at,
                ])->all();
            }),
            'docs' => $this->whenLoaded('docs', function () {
                return $this->docs->map(fn ($doc) => [
                    'id' => $doc->id,
                    'registry_id' => $doc->registry_id,
                    'docs_type' => $doc->docs_type,
                    'docs_type_name' => $doc->documentTypeLibrary?->name,
                    'file_path' => $doc->file_path,
                    'created_at' => $doc->created_at,
                    'updated_at' => $doc->updated_at,
                ])->all();
            }),
            'repo' => $this->whenLoaded('repo', function () {
                $repo = $this->repo;
                return $repo ? [
                    'id' => $repo->id,
                    'registry_id' => $repo->registry_id,
                    'staging_url' => $repo->staging_url,
                    'production_url' => $repo->production_url,
                    'repo_git' => $repo->repo_git,
                    'development_solution_type' => $repo->development_solution_type,
                    'development_solution_type_name' => $repo->developmentSolutionTypeLibrary?->name,
                    'created_at' => $repo->created_at,
                    'updated_at' => $repo->updated_at,
                ] : null;
            }),
        ];
    }
}
