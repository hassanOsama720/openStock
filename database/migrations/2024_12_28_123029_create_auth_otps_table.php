<?php

use App\Enums\OTPType;
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
        Schema::create('auth_otps', function (Blueprint $table) {
            $table->id();
            $table->string('identifier');
            $table->string('country_code')->nullable();
            $table->string('code');
            $table->enum('type', OTPType::toArray());
            $table->morphs('otpable');
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_otps');
    }
};
