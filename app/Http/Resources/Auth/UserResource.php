<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'last_name'    => $this->last_name,
            'currency'     => $this->currency,
            'sub_domain'   => $this->sub_domain,
            'phone'        => $this->phone,
            'email'        => $this->email,
            'token'        => $this->when($request->routeIs('login'), request()->header('authorization') ?? $this->login()),
            'store'         => $this->when($request->routeIs('profile'), StoreResource::make($this->store)),
        ];
    }
}
