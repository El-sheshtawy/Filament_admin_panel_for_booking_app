<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apartment_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('price');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apartment_prices');
    }
};
