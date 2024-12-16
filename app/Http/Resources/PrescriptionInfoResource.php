<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionInfoResource extends JsonResource
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
            'prescription_id' => $this->prescription_id,
            'medication' => $this->medication ? [
                'id' => $this->medication->id,
                'name' => $this->medication->name,
                'category_id' => $this->medication->category_id
            ] : null,
            'instructions' => $this->instructions,
            'duration' => $this->duration,
            'quantity' => $this->quantity,
        ];
    }
}
