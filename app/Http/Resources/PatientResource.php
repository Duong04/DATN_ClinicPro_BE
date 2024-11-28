<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "insurance_number" => $this->insurance_number,
            "user_id" => $this->user_id,
            "identity_card_id" => $this->identity_card_id,
            "identity_card" => $this->identity_card,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "patient_info" => [
                "fullname" => $this->patientInfo->fullname ?? null,
                "avatar" => $this->patientInfo->avatar ?? null,
                "email" => $this->patientInfo->email ?? null,
                "phone_number" => $this->patientInfo->phone_number ?? null,
                "address" => $this->patientInfo->address ?? null,
                "dob" => $this->patientInfo->dob ?? null,
                "gender" => $this->patientInfo->gender ?? null,
            ],
            "medical_histories" => MedicalResource::collection($this->medicalHistories)
        ];
    }
}
