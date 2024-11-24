<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $patient = $this?->patient?->patientInfo;
        $userInfo = $this->role->name == 'patient' ? $patient : $this->userInfo;
        return [
            'id' => $this->id,
            'email' => $this->email,
            'status' => $this->status,
            'role' => new RoleResource($this->role),
            'user_info' => [
                'fullname' => $userInfo?->fullname,
                'avatar' => $userInfo?->avatar
            ]
        ];
    }
}
