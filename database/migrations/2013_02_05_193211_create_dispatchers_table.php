<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dispatchers', function (Blueprint $table) {
            $table->id(); // bigint unsigned, auto increment
            $table->foreignId('user_id')->constrained('users'); // FK para users (se users existir antes)
            $table->timestamps();

            $table->string('type', 255)->default('individual');
            $table->string('company_name', 255)->nullable();
            $table->string('ssn_itin', 50)->nullable();
            $table->string('ein_tax_id', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->text('notes')->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('departament', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatchers');
    }
};
