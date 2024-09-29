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
                'id' => $this?->doctor?->id,
                'specialty' => $this?->doctor?->specialty,
                'email' => $this?->doctor?->user?->email,
                'fullname' => $this?->doctor?->user?->userInfo?->fullname,
                'phone_number' => $this?->doctor?->user?->userInfo?->phone_number,
                'gender' => $this?->doctor?->user?->userInfo?->gender
            ],
            'patient' => [
                'id' => $this?->patient?->id,
                'fullname' => $this?->patient?->patientInfo?->fullname,
                'email' => $this?->patient?->patientInfo?->email,
                'phone_number' => $this?->patient?->patientInfo?->phone_number,
                'gender' => $this?->patient?->patientInfo?->gender
            ]
        ];
    }
}
