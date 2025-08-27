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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis'); // API, Database, File, etc.
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Dalam Pembangunan'])->default('Tidak Aktif');
            $table->text('konfigurasi')->nullable(); // JSON configuration
            $table->text('penerangan')->nullable();
            $table->string('url_endpoint')->nullable();
            $table->string('api_key')->nullable();
            $table->timestamp('terakhir_sync')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['status', 'jenis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
