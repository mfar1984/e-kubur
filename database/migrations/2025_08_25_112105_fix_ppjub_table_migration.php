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
        Schema::table('ppjub', function (Blueprint $table) {
            // Check if zon column exists before dropping
            if (Schema::hasColumn('ppjub', 'zon')) {
                $table->dropColumn('zon');
            }
            
            // Check if email column doesn't exist before adding
            if (!Schema::hasColumn('ppjub', 'email')) {
                $table->string('email')->after('telefon');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppjub', function (Blueprint $table) {
            // Check if email column exists before dropping
            if (Schema::hasColumn('ppjub', 'email')) {
                $table->dropColumn('email');
            }
            
            // Check if zon column doesn't exist before adding
            if (!Schema::hasColumn('ppjub', 'zon')) {
                $table->string('zon')->after('alamat');
            }
        });
    }
};
