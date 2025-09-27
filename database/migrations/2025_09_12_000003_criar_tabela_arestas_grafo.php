<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração para criação da tabela de arestas dos grafos
 * 
 * Esta migração cria a tabela que armazena as arestas (ligações)
 * entre os nós dos grafos com seus pesos e propriedades visuais.
 */
return new class extends Migration
{
    /**
     * Executa a migração - Criar tabela arestas_grafo
     */
    public function up(): void
    {
        Schema::create('arestas_grafo', function (Blueprint $table) {
            $table->id();
            
            // Relacionamento com grafo
            $table->foreignId('id_grafo')
                  ->constrained('grafos', 'id')
                  ->onDelete('cascade')
                  ->comment('ID do grafo ao qual esta aresta pertence');
            
            // Relacionamentos com nós
            $table->foreignId('id_no_origem')
                  ->constrained('nos_grafo', 'id')
                  ->onDelete('cascade')
                  ->comment('ID do nó de origem da aresta');
                  
            $table->foreignId('id_no_destino')
                  ->constrained('nos_grafo', 'id')
                  ->onDelete('cascade')
                  ->comment('ID do nó de destino da aresta');
            
            // Propriedades da aresta
            $table->integer('peso')->default(1)->comment('Peso da aresta (-1, 0, 1 ou outros valores)');
            
            // Propriedades visuais
            $table->string('cor', 7)->nullable()->comment('Cor da aresta em formato hexadecimal');
            $table->integer('largura')->default(2)->comment('Largura da linha da aresta');
            
            // Propriedades adicionais (JSON)
            $table->json('propriedades')->nullable()->comment('Propriedades customizadas da aresta');
            
            // Campos de auditoria
            $table->timestamps();
            
            // Índices para performance
            $table->index(['id_grafo'], 'idx_arestas_grafo_id');
            $table->index(['id_no_origem'], 'idx_arestas_no_origem');
            $table->index(['id_no_destino'], 'idx_arestas_no_destino');
            $table->index(['peso'], 'idx_arestas_peso');
            $table->index(['id_no_origem', 'id_no_destino'], 'idx_arestas_origem_destino');
            
            // Constraint para evitar arestas duplicadas no mesmo sentido
            $table->unique(['id_grafo', 'id_no_origem', 'id_no_destino'], 'uk_arestas_grafo_nos');
            
            // SQLite constraint para evitar autolaços (origem ≠ destino)
            // Removido para simplicidade no SQLite
        });
        
        // SQLite não suporta comentários de tabela  
        // Comentário: Tabela para armazenamento das arestas (ligações) entre nós dos grafos
    }

    /**
     * Reverte a migração - Remover tabela arestas_grafo
     */
    public function down(): void
    {
        Schema::dropIfExists('arestas_grafo');
    }
};
