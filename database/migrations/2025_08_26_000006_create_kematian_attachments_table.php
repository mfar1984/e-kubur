<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('kematian_attachments')) {
            Schema::create('kematian_attachments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('kematian_id');
                $table->string('filename');
                $table->string('path');
                $table->string('mime_type', 150)->nullable();
                $table->unsignedBigInteger('size_bytes')->nullable();
                $table->unsignedBigInteger('uploaded_by')->nullable();
                $table->timestamps();

                $table->foreign('kematian_id')->references('id')->on('kematian')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kematian_attachments');
    }
};


