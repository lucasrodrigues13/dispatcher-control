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
        Schema::table('plans', function (Blueprint $table) {
            // Adicionar user_id para planos customizados (nullable = planos globais)
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            
            // Adicionar max_dispatchers que estava faltando
            $table->integer('max_dispatchers')->default(1)->after('max_carriers');
            
            // Adicionar flag para identificar planos customizados
            $table->boolean('is_custom')->default(false)->after('active');
            
            // Ajustar unique do slug: único para planos globais, ou único por user_id para customizados
            $table->dropUnique(['slug']);
        });
        
        // Criar índice composto para slug único por user_id (NULL = global)
        Schema::table('plans', function (Blueprint $table) {
            $table->unique(['slug', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropUnique(['slug', 'user_id']);
            $table->unique('slug');
            
            $table->dropColumn(['user_id', 'max_dispatchers', 'is_custom']);
        });
    }
};


