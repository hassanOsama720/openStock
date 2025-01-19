<?php

namespace App\Http\Resources\Notification;

use App\Http\Resources\Notification\NotificationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationsCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'data'       => NotificationResource::collection($this->collection),
            'pagination' => [
                'total'        => $this->total(),
                'count'        => $this->count(),
                'per_page'     => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages'  => $this->lastPage(),
            ],
        ];

    }
}

