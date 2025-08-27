<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('api_configurations', function (Blueprint $table) {
            if (!Schema::hasColumn('api_configurations', 'token_default_expiry')) {
                $table->string('token_default_expiry', 20)->nullable();
            }
            if (!Schema::hasColumn('api_configurations', 'allowed_origins')) {
                $table->text('allowed_origins')->nullable();
            }
            if (!Schema::hasColumn('api_configurations', 'default_abilities')) {
                $table->text('default_abilities')->nullable(); // JSON array of abilities
            }
            if (!Schema::hasColumn('api_configurations', 'token_name')) {
                $table->string('token_name', 100)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('api_configurations', function (Blueprint $table) {
            if (Schema::hasColumn('api_configurations', 'token_default_expiry')) {
                $table->dropColumn('token_default_expiry');
            }
            if (Schema::hasColumn('api_configurations', 'allowed_origins')) {
                $table->dropColumn('allowed_origins');
            }
            if (Schema::hasColumn('api_configurations', 'default_abilities')) {
                $table->dropColumn('default_abilities');
            }
            if (Schema::hasColumn('api_configurations', 'token_name')) {
                $table->dropColumn('token_name');
            }
        });
    }
};


