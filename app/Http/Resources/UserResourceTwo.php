<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResourceTwo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $patient = $this?->patient?->patientInfo;
        $patient['identity_card'] = $this?->patient?->identityCard;
        return [
            'id' => $this->id,
            'email' => $this->email,
            'status' => $this->status,
            'role' => $this->role,
            'user_info' => $this->role->name == 'patient' ? $patient : $this->userInfo
        ];
    }
}
