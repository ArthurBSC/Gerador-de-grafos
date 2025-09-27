<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração para criação da tabela de nós dos grafos
 * 
 * Esta migração cria a tabela que armazena os nós (vértices)
 * dos grafos com suas posições e propriedades visuais.
 */
return new class extends Migration
{
    /**
     * Executa a migração - Criar tabela nos_grafo
     */
    public function up(): void
    {
        Schema::create('nos_grafo', function (Blueprint $table) {
            $table->id();
            
            // Relacionamento com grafo
            $table->foreignId('id_grafo')
                  ->constrained('grafos', 'id')
                  ->onDelete('cascade')
                  ->comment('ID do grafo ao qual este nó pertence');
            
            // Propriedades do nó
            $table->string('rotulo', 100)->comment('Rótulo/nome do nó para exibição');
            
            // Posicionamento visual
            $table->decimal('posicao_x', 8, 2)->nullable()->comment('Posição X no plano de visualização');
            $table->decimal('posicao_y', 8, 2)->nullable()->comment('Posição Y no plano de visualização');
            
            // Propriedades visuais
            $table->string('cor', 7)->default('#3498db')->comment('Cor do nó em formato hexadecimal');
            $table->integer('tamanho')->default(20)->comment('Tamanho do nó para visualização');
            
            // Propriedades adicionais (JSON)
            $table->json('propriedades')->nullable()->comment('Propriedades customizadas do nó');
            
            // Campos de auditoria
            $table->timestamps();
            
            // Índices para performance
            $table->index(['id_grafo'], 'idx_nos_grafo_id');
            $table->index(['rotulo'], 'idx_nos_rotulo');
            $table->index(['posicao_x', 'posicao_y'], 'idx_nos_posicao');
            
            // Constraint para garantir rótulos únicos dentro do mesmo grafo
            $table->unique(['id_grafo', 'rotulo'], 'uk_nos_grafo_rotulo');
        });
        
        // SQLite não suporta comentários de tabela
        // Comentário: Tabela para armazenamento dos nós (vértices) dos grafos
    }

    /**
     * Reverte a migração - Remover tabela nos_grafo
     */
    public function down(): void
    {
        Schema::dropIfExists('nos_grafo');
    }
};
