<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'type'    => $this->type,
            'data'    => $this->data,
            'read_at' => Carbon::parse($this->read_at)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
