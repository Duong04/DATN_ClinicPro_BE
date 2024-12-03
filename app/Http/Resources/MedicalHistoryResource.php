<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'files' => $this->files,
            'doctor' => [
                'id' => $this->user?->id,
                'specialty' => $this->user?->doctor?->specialty->name,
                'email' => $this?->user?->email,
                'fullname' => $this?->user?->userInfo?->fullname,
                'phone_number' => $this?->user?->userInfo?->phone_number,
                'gender' => $this?->user?->userInfo?->gender,
                'avatar' => $this?->user?->userInfo?->avatar,
            ],
            'patient' => [
                'id' => $this?->patient?->id,
                'fullname' => $this?->patient?->patientInfo?->fullname,
                'email' => $this?->patient?->patientInfo?->email,
                'phone_number' => $this?->patient?->patientInfo?->phone_number,
                'gender' => $this?->patient?->patientInfo?->gender,
                'avatar' => $this?->patient?->patientInfo?->avatar,
            ]
        ];
    }
}
