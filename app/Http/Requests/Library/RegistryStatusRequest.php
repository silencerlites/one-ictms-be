<?php

namespace App\Http\Requests\Library;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistryStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Routes enforce auth:api; enforce admin permissions on mutating routes.
        return true;
    }

    public function rules(): array
    {
        $method = (string) $this->route()?->getActionMethod();
        $table = 'registry_statuses';
        $id = ['required', 'integer', Rule::exists($table, 'id')];
        if (str_ends_with($method, 'View') || str_ends_with($method, 'Delete')) {
            return ['id' => $id];
        }
        if (str_ends_with($method, 'Create') || str_ends_with($method, 'Update')) {
            $isUpdate = str_ends_with($method, 'Update');
            return array_merge(
                $isUpdate ? ['id' => $id] : [],
                [
                    'name' => [$isUpdate ? 'sometimes' : 'required', 'string', 'max:255'],
                    
                    'is_active' => ['sometimes', 'boolean'],
                ]
            );
        }
        return [];
    }
}
