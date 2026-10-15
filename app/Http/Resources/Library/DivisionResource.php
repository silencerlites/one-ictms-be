<?php

namespace App\Http\Resources\Library;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DivisionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'department_id' => $this->department_id,
            'alias' => $this->alias,
            'name' => $this->name,
            'department' => $this->department ? [
                'id' => $this->department->id,
                'alias' => $this->department->alias,
                'name' => $this->department->name,
            ] : null,
        ];
    }
}
