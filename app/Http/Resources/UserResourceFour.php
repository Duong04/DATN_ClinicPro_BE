<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResourceFour extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $users = [
            'id' => $this?->user?->id,
            'email' => $this?->user?->email,
            'status' => $this?->user?->status,
            'fullname' => $this?->fullname,
            'avatar' => $this?->avatar,
            'address' => $this?->address,
            'phone_number' => $this?->phone_number,
            'gender' => $this?->gender,
            'dob' => $this?->dob
        ];

        if (isset($this->user->doctor) && $this->user->doctor !== null) {
            $users['doctor'] = [
                'id' => $this?->user?->doctor?->id,
                'specialty' => $this?->user?->doctor?->specialty?->name,
                'description' => $this?->user?->doctor?->specialty?->description,
            ];
        }

        return $users;
    }
}
