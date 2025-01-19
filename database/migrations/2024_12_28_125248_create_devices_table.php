<?php

use App\Enums\DeviceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->longText('device_id');
            $table->enum('device_type', DeviceType::toArray())->nullable();
            $table->string('mac_address')->nullable();
            $table->morphs('devicable');
            $table->string('preferred_locale')->nullable();
            $table->boolean('is_current');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
