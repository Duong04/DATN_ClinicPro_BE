<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResourceSix extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $users = [
            'id' => $this->id,
            'email' => $this->email,
            'status' => $this->status,
            'fullname' => $this->userInfo->fullname,
            'avatar' => $this->userInfo->avatar,
            'address' => $this->userInfo->address,
            'phone_number' => $this->userInfo->phone_number,
            'gender' => $this->userInfo->gender,
            'dob' => $this->userInfo->dob
        ];

        if (isset($this->doctor) && $this->doctor !== null) {
            $users['doctor'] = [
                'id' => $this->doctor?->id,
                'specialty' => $this->doctor?->specialty?->name,
                'description' => $this->doctor?->specialty?->description,
            ];
        }
        return $users;
    }
}
