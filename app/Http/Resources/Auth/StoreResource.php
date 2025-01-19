<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'logo'         => $this->getFirstMediaUrl('logo'),
            'currency'     => $this->currency,
            'commercial_register' => $this->commercial_register,
            'commercial_register_image' => $this->getFirstMediaUrl('commercial_register_image'),
            'addresses'    => AddressResource::collection($this->addresses),
        ];
    }
}
