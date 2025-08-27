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
        Schema::dropIfExists('kariah');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('kariah', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_ic', 14);
            $table->string('telefon');
            $table->text('alamat');
            $table->string('bangsa');
            $table->string('zon');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->date('tarikh_keahlian');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('nama');
            $table->index('no_ic');
            $table->index('zon');
            $table->index('status');
            $table->index('tarikh_keahlian');

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }
};
