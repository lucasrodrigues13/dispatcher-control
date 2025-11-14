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
        if (Schema::hasTable('plans')) {
            Schema::table('plans', function (Blueprint $table) {
                if (!Schema::hasColumn('plans', 'max_brokers')) {
                    $table->integer('max_brokers')->default(0)->after('max_drivers');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('plans')) {
            Schema::table('plans', function (Blueprint $table) {
                if (Schema::hasColumn('plans', 'max_brokers')) {
                    $table->dropColumn('max_brokers');
                }
            });
        }
    }
};
