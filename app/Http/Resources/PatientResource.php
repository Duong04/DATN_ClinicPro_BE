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
        $user = null;
        if ($this->user_id !== null) {
            $user = [
                'id' => $this->user->id,
                'status' => $this->user->status
            ];
        }

        return [
            "id" => $this->id,
            "insurance_number" => $this->insurance_number,
            "identity_card" => $this->identityCard,
            "status" => $this->status ?? null,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "user" => $user,
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
