<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'appointment_date' => $this->appointment_date,
            'deposit_amount' => $this->deposit_amount,
            'booking_type' => $this->booking_type,
            'appointment_type' => $this->appointment_type,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'cancellation_reason' => $this->cancellation_reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'description' => $this->description,
            'patient' => $this->patient ? [
                'id' => $this->patient->id,
                'insurance_number' => $this->patient->insurance_number,
                'identity_card_id' => $this->patient->identity_card_id,
                'status' => $this->patient->status,
            ] : null,
            'specialty' => $this->specialty ? [
                'id' => $this->specialty->id,
                'name' => $this->specialty->name,
                'description' => $this->specialty->description
            ] : null,
            'package' => $this->package ? [
                'id' => $this->package->id,
                'name' => $this->package->name,
                'description' => $this->package->description,
                'content' => $this->package->content,
                'image' => $this->package->image,
                'slug' => $this->package->slug,
            ] : null,

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
        ];
    }
}
