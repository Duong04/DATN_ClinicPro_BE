<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResourceThree extends JsonResource
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
        $patient['insurance_number'] = $this?->patient?->insurance_number;
        return [
            'id' => $this->id,
            'email' => $this->email,
            'status' => $this->status,
            'user_info' => $this->role->name == 'patient' ? $patient : $this->userInfo
        ];
    }
}
