<?php
namespace App\Http\Requests\Registry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class RegistryRequest extends FormRequest {
    public function authorize(): bool { return true; } // Protect mutations with RBAC in routes.
    public function rules(): array {
        $action = $this->route()?->getActionMethod();
        if (in_array($action,['view','delete'],true)) return ['id'=>['required','integer',Rule::exists('registries','id')]];
        if (!in_array($action,['create','update'],true)) return [];
        $create=$action==='create'; $required=$create?'required':'sometimes';
        $active = static fn (string $table) => Rule::exists($table,'id')->where('is_active',true);
        $rules = [
            'system_name'=>[$required,'string','max:255'],
            'alias'=>['sometimes','nullable','string','max:100'],
            'description'=>['sometimes','nullable','string'],
            'business_owner'=>['sometimes','nullable','integer',$active('departments')],
            'status'=>[$required,'integer',$active('registry_statuses')],
            'desc'=>['sometimes','nullable','array'],
            'desc.managing_type'=>['sometimes','nullable','integer',$active('managing_types')],
            'desc.managing_office'=>['sometimes','nullable','integer',$active('departments')],
            'desc.third_party_company'=>['sometimes','nullable','string','max:255'],
            'desc.project_roll_on'=>['sometimes','nullable','date'],
            'desc.project_sign_off'=>['sometimes','nullable','date','after_or_equal:desc.project_roll_on'],
            'desc.estimation_project_duration'=>['sometimes','nullable','integer','min:0'],
            'repo'=>['sometimes','nullable','array'],
            'repo.staging_url'=>['sometimes','nullable','url','max:2048'],
            'repo.production_url'=>['sometimes','nullable','url','max:2048'],
            'repo.repo_git'=>['sometimes','nullable','url','max:2048'],
            'repo.development_solution_type'=>['sometimes','nullable','integer',$active('development_solution_types')],
            'tech_stack'=>['sometimes','nullable','array'],
            'tech_stack.frontend_stack'=>['sometimes','nullable','array'],
            'tech_stack.frontend_stack.*'=>['integer',$active('tech_stacks')],
            'tech_stack.backend_stack'=>['sometimes','nullable','array'],
            'tech_stack.backend_stack.*'=>['integer',$active('tech_stacks')],
            'tech_stack.database'=>['sometimes','nullable','string','max:255'],
            'tech_stack.server_type'=>['sometimes','nullable','string','max:255'],
            'tech_stack.server_name'=>['sometimes','nullable','string','max:255'],
            'stakeholders'=>['sometimes','array'],
            'stakeholders.*.user_id'=>['required','integer',Rule::exists('users','id')],
            'stakeholders.*.stakeholder_type'=>['required','integer',$active('stakeholder_types')],
            'docs'=>['sometimes','array'],
            'docs.*.docs_type'=>['required','integer',$active('document_types')],
            'docs.*.file_path'=>['required','string','max:2048'],
        ];
        if (!$create) $rules['id']=['required','integer',Rule::exists('registries','id')];
        return $rules;
    }
}
