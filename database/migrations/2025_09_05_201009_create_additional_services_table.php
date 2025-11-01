<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('additional_services', function (Blueprint $table) {
            $table->id(); // bigint

            $table->string('describe', 255);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('value', 10, 2);
            $table->decimal('total', 10, 2)->nullable();

            // parcelas
            $table->boolean('is_installment')->default(false);
            // pode usar enum; no Postgres o Laravel cria check constraint
            $table->enum('installment_type', ['weeks', 'months'])->nullable();
            $table->integer('installment_count')->nullable();

            $table->string('status', 45)->nullable();

            // FKs (ajuste os nomes das tabelas se forem diferentes)
            $table->foreignId('carrier_id')->constrained('carriers');
            $table->foreignId('dispatcher_id')->constrained('dispatchers');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('additional_services');
    }
};
