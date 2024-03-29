<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apartment_facility', function (Blueprint $table) {
            $table->foreignId('apartment_id')->constrained();
            $table->foreignId('facility_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apartment_facility');
    }
};
