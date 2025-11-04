<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('loads')) {
            Schema::table('loads', function (Blueprint $table) {
                if (!Schema::hasColumn('loads', 'payment_status')) {
                    $table->string('payment_status', 100)->nullable()->after('payment_notes');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('loads')) {
            Schema::table('loads', function (Blueprint $table) {
                if (Schema::hasColumn('loads', 'payment_status')) {
                    $table->dropColumn('payment_status');
                }
            });
        }
    }
};

