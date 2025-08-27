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
        Schema::create('email_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('smtp_host')->default('smtp.gmail.com');
            $table->integer('smtp_port')->default(587);
            $table->string('username')->default('noreply@ekubur.com');
            $table->string('password');
            $table->string('encryption')->default('TLS');
            $table->string('authentication')->default('Required');
            $table->string('from_name')->default('E-Kubur System');
            $table->string('reply_to')->default('support@ekubur.com');
            $table->integer('connection_timeout')->default(30);
            $table->integer('max_retries')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_test')->nullable();
            $table->string('last_test_status')->nullable();
            $table->text('last_test_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_configurations');
    }
};
