<?php

namespace App\Services;

use App\Models\ContactUsMessage;

class ContactUsMessageService
{
   public function store(array $data): array
   {
       try {
        $message = ContactUsMessage::create($data);
        return [
            'status' => 'success',
            'message' => __('apis.contact_us_message_stored')
        ];
       }catch (\Exception $e) {
          return [
            'status' => 'fail',
            'message' => __('apis.contact_us_message_not_stored')
          ];
         }
   }
}
