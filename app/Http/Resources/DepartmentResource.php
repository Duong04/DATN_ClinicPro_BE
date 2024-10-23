<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $department = [
            'id' => $this?->id,
            'name' => $this?->name,
            'description' => $this?->description,
            'users_count' => $this?->users_count,
            'manager' => [
                'id' => $this?->manager?->id,
                'email' => $this?->manager?->email,
                'fullname' => $this?->manager?->userInfo?->fullname,
                'avatar' => $this?->manager?->userInfo?->avatar,
                'address' => $this?->manager?->userInfo?->address,
                'phone_number' => $this?->manager?->userInfo?->phone_number,
                'gender' => $this?->manager?->userInfo?->gender,
                'dob' => $this?->manager?->userInfo?->dob
            ],
            'users' => UserResourceFour::collection($this->users)
        ];

        return $department;
    }
}
