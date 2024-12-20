<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PermissionResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $id = $request->route('id');

        $roles = [
            'id' => $this?->id,
            'name' => $this?->name,
            'description' => $this?->description,
            "created_at" => $this->created_at,
            "updated_at" =>  $this->updated_at,
            'users_count' => $this->users_count,
            'users' => [],
        ];

        if ($id) {
            $roles['permissions'] = PermissionResource::collection($this->permissions?->unique('id') ?? collect());
        }

        foreach ($this->users as $user) {
            $info = $this->name === 'patient' 
                ? $user->patient?->patientInfo 
                : $user->userInfo;

            if ($info) {
                $roles['users'][] = [
                    'id' => $user->id,
                    'fullname' => $info->fullname,
                    'avatar' => $info->avatar,
                ];
            }
        }

        return $roles;
    }

}
