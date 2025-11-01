<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Usar SQL direto - muito mais rápido
        DB::statement("
            ALTER TABLE carriers
            ADD COLUMN company_name VARCHAR(255) NULL AFTER id,
            ADD COLUMN contact_name VARCHAR(255) NULL AFTER company_name,
            ADD COLUMN about TEXT NULL AFTER contact_name,
            ADD COLUMN website VARCHAR(255) NULL AFTER about,
            ADD COLUMN trailer_capacity INT NULL AFTER website,
            ADD COLUMN is_auto_hauler TINYINT(1) DEFAULT 0 AFTER trailer_capacity,
            ADD COLUMN is_towing TINYINT(1) DEFAULT 0 AFTER is_auto_hauler,
            ADD COLUMN is_driveaway TINYINT(1) DEFAULT 0 AFTER is_towing,
            ADD COLUMN contact_phone VARCHAR(20) NULL AFTER is_driveaway,
            ADD COLUMN city VARCHAR(100) NULL AFTER address,
            ADD COLUMN state VARCHAR(100) NULL AFTER city,
            ADD COLUMN zip VARCHAR(20) NULL AFTER state,
            ADD COLUMN country VARCHAR(100) NULL AFTER zip
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carriers', function (Blueprint $table) {
            $table->dropColumn([
                'company_name', 'contact_name', 'about', 'website',
                'trailer_capacity', 'is_auto_hauler', 'is_towing',
                'is_driveaway', 'contact_phone', 'city', 'state',
                'zip', 'country'
            ]);
        });
    }
};
