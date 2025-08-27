<?php

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
        Schema::create('weather_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->default('OpenWeatherMap');
            $table->string('api_key');
            $table->string('base_url')->default('https://api.openweathermap.org/data/2.5');
            $table->string('default_location')->default('Kuala Lumpur, MY');
            $table->decimal('latitude', 10, 7)->default(3.1390);
            $table->decimal('longitude', 10, 7)->default(101.6869);
            $table->string('units')->default('metric');
            $table->string('language')->default('ms');
            $table->integer('update_frequency')->default(30); // in minutes
            $table->integer('cache_duration')->default(15); // in minutes
            $table->timestamp('last_update')->nullable();
            $table->string('current_weather')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_configurations');
    }
};
