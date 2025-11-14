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
        if (Schema::hasTable('loads')) {
            Schema::table('loads', function (Blueprint $table) {
                if (!Schema::hasColumn('loads', 'deleted_at')) {
                    $table->softDeletes()->after('updated_at');
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
                if (Schema::hasColumn('loads', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};
