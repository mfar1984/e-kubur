<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('base_url');
            $table->string('version', 50)->default('v1');
            $table->string('auth_type', 100)->default('Bearer Token');
            $table->text('api_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->text('access_token')->nullable();
            $table->string('rate_limit', 100)->nullable();
            $table->string('timeout', 50)->nullable();
            $table->string('max_retries', 50)->nullable();
            $table->string('ssl_verification', 50)->nullable();
            $table->string('logging_level', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_configurations');
    }
};


