<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('kematian') && !Schema::hasColumn('kematian', 'catatan')) {
            Schema::table('kematian', function (Blueprint $table) {
                $table->text('catatan')->nullable()->after('telefon_waris');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('kematian') && Schema::hasColumn('kematian', 'catatan')) {
            Schema::table('kematian', function (Blueprint $table) {
                $table->dropColumn('catatan');
            });
        }
    }
};


