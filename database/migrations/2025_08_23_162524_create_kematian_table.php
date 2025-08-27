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
        Schema::create('kematian', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tarikh_lahir');
            $table->string('no_ic', 14);
            $table->date('tarikh_meninggal');
            $table->decimal('longitude', 10, 6);
            $table->decimal('latitude', 10, 6);
            $table->string('waris');
            $table->string('telefon_waris');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('nama');
            $table->index('no_ic');
            $table->index('tarikh_meninggal');
            $table->index('waris');

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kematian');
    }
};
