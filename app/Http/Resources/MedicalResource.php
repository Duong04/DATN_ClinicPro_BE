<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalResource extends JsonResource
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
                'avatar' => $this?->user?->userInfo?->avatar,
            ]
        ];
    }
}