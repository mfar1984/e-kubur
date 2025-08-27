<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('api_configurations')) {
            Schema::table('api_configurations', function (Blueprint $table) {
                if (Schema::hasColumn('api_configurations', 'api_key')) {
                    $table->dropColumn('api_key');
                }
                if (Schema::hasColumn('api_configurations', 'secret_key')) {
                    $table->dropColumn('secret_key');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('api_configurations')) {
            Schema::table('api_configurations', function (Blueprint $table) {
                if (!Schema::hasColumn('api_configurations', 'api_key')) {
                    $table->text('api_key')->nullable();
                }
                if (!Schema::hasColumn('api_configurations', 'secret_key')) {
                    $table->text('secret_key')->nullable();
                }
            });
        }
    }
};


