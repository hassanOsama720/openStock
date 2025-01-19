<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notification::create([
            'type' => 'App\Notifications\InvoicePaid',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => 1,
            'data' => '{"invoice_id": "1", "amount": 1000}',
        ]);
    }
}
