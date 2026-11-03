<?php

namespace App\Http\Requests\Library;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authenticated routes also need appropriate access control.
    }

    public function rules(): array
    {
        $action = $this->route()?->getActionMethod();
        $entity = 'departments';

        if ($action === 'departmentUsers') {
            return [
                'department_id' => [
                    'required', 'integer',
                    Rule::exists('departments', 'id')->where('is_active', true),
                ],
            ];
        }

        if (str_ends_with((string) $action, 'View') || str_ends_with((string) $action, 'Delete')) {
            return ['id' => ['required', 'integer', Rule::exists($entity, 'id')]];
        }

        if (str_ends_with((string) $action, 'Create') || str_ends_with((string) $action, 'Update')) {
            $update = str_ends_with((string) $action, 'Update');
            $required = $update ? 'sometimes' : 'required';
            $rules = [
                'alias' => [$required, 'string', 'max:100'],
                'name' => [$required, 'string', 'max:255'],
                'is_active' => ['sometimes', 'boolean'],
            ];
            if ($update) {
                $rules['id'] = ['required', 'integer', Rule::exists($entity, 'id')];
            }
            return $rules;
        }

        return [];
    }
}
