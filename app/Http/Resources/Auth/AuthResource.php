<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'details' => $this->whenLoaded('details', fn () => [
                'first_name' => $this->details->first_name,
                'middle_name' => $this->details->middle_name,
                'last_name' => $this->details->last_name,
                'ext_name' => $this->details->ext_name,
                'sex' => $this->details->sex,
                'position_id' => $this->details->position_id,
                'department_id' => $this->details->department_id,
                'division_id' => $this->details->division_id,
            ]),
        ];
    }
}
