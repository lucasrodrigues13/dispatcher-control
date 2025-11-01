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
        if (Schema::hasTable('brokers')) {
            Schema::table('brokers', function (Blueprint $table) {
                // garanta a nulabilidade correta para o FK se pretende nullOnDelete
                if (Schema::hasColumn('brokers', 'user_id')) {
                    // se precisar, ajuste manualmente via SQL para tornar nullable
                    // DB::statement('ALTER TABLE brokers MODIFY user_id BIGINT UNSIGNED NULL');
                }

                // adicione apenas as colunas que não existirem
                if (!Schema::hasColumn('brokers', 'accounting_email')) {
                    $table->string('accounting_email')->nullable()->after('updated_at');
                }
                if (!Schema::hasColumn('brokers', 'accounting_phone_number')) {
                    $table->string('accounting_phone_number', 50)->nullable()->after('accounting_email');
                }
                if (!Schema::hasColumn('brokers', 'fee_percent')) {
                    $table->integer('fee_percent')->nullable()->after('accounting_phone_number');
                }
                if (!Schema::hasColumn('brokers', 'payment_terms')) {
                    $table->text('payment_terms')->nullable()->after('fee_percent');
                }
                if (!Schema::hasColumn('brokers', 'payment_method')) {
                    $table->string('payment_method', 100)->nullable()->after('payment_terms');
                }
            });
            return; // não tenta criar de novo
        }

        Schema::create('brokers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // <- corrigido
            $table->string('license_number')->nullable();
            $table->string('company_name')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->string('accounting_email')->nullable();
            $table->string('accounting_phone_number', 50)->nullable();
            $table->integer('fee_percent')->nullable();
            $table->text('payment_terms')->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->index('user_id');
        });
    }
};
