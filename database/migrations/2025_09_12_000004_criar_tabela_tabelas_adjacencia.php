<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração para criação da tabela de matrizes de adjacência
 * 
 * Esta migração cria a tabela que armazena as matrizes de adjacência
 * dos grafos para análise matemática e exportação.
 */
return new class extends Migration
{
    /**
     * Executa a migração - Criar tabela tabelas_adjacencia
     */
    public function up(): void
    {
        Schema::create('tabelas_adjacencia', function (Blueprint $table) {
            $table->id();
            
            // Relacionamento com grafo
            $table->foreignId('id_grafo')
                  ->constrained('grafos', 'id')
                  ->onDelete('cascade')
                  ->comment('ID do grafo ao qual esta matriz pertence');
            
            // Informações da matriz
            $table->string('nome', 255)->comment('Nome descritivo da matriz');
            $table->enum('tipo', ['adjacencia', 'incidencia', 'laplaciana', 'personalizada'])
                  ->default('adjacencia')
                  ->comment('Tipo da matriz armazenada');
            
            // Dados da matriz (JSON)
            $table->json('matriz')->comment('Matriz bidimensional armazenada como JSON');
            $table->json('rotulos_linhas')->nullable()->comment('Rótulos das linhas da matriz');
            $table->json('rotulos_colunas')->nullable()->comment('Rótulos das colunas da matriz');
            
            // Campos de auditoria
            $table->timestamps();
            
            // Índices para performance
            $table->index(['id_grafo'], 'idx_tabelas_grafo_id');
            $table->index(['tipo'], 'idx_tabelas_tipo');
            $table->index(['created_at'], 'idx_tabelas_criacao');
            
            // Constraint para garantir uma matriz de adjacência por grafo
            $table->unique(['id_grafo', 'tipo'], 'uk_tabelas_grafo_tipo');
        });
        
        // SQLite não suporta comentários de tabela
        // Comentário: Tabela para armazenamento de matrizes de adjacência e outras representações matriciais dos grafos
    }

    /**
     * Reverte a migração - Remover tabela tabelas_adjacencia
     */
    public function down(): void
    {
        Schema::dropIfExists('tabelas_adjacencia');
    }
};
