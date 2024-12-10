<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'status' => $this->user->status,
                'role' => $this->user->role ? [
                    'id' => $this->user->role->id,
                    'name' => $this->user->role->name,
                    'description' => $this->user->role->description,
                ] : null,
                'user_info' => $this->user->userInfo,
            ] : null,
            'patient' => $this->patient ? [
                'id' => $this->patient->id,
                'insurance_number' => $this->patient->insurance_number,
                'identity_card_id' => $this->patient->identity_card_id,
                'status' => $this->patient->status,
                'patient_info' => $this->patient->patientInfo,
            ] : null,
            'medical_histories' => $this->medical_histories ? [
                'id' => $this->id,
                'description' => $this->description,
                'diagnosis' => $this->diagnosis,
                'treatment' => $this->treatment,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'medications' => PrescriptionInfoResource::collection($this->prescription_Infos)
        ];
    }
}
