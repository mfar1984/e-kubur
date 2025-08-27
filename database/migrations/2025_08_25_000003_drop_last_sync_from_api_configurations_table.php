<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('api_configurations') && Schema::hasColumn('api_configurations', 'last_sync')) {
            Schema::table('api_configurations', function (Blueprint $table) {
                $table->dropColumn('last_sync');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('api_configurations') && !Schema::hasColumn('api_configurations', 'last_sync')) {
            Schema::table('api_configurations', function (Blueprint $table) {
                $table->timestamp('last_sync')->nullable();
            });
        }
    }
};


