<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração para criação da tabela de grafos
 * 
 * Esta migração cria a tabela principal que armazena os grafos
 * do sistema com suas propriedades básicas e configurações.
 */
return new class extends Migration
{
    /**
     * Executa a migração - Criar tabela grafos
     */
    public function up(): void
    {
        Schema::create('grafos', function (Blueprint $table) {
            $table->id();
            
            // Informações básicas do grafo
            $table->string('nome', 255)->comment('Nome identificador do grafo');
            $table->text('descricao')->nullable()->comment('Descrição detalhada do grafo');
            $table->enum('tipo', ['direcionado', 'nao_direcionado'])
                  ->default('nao_direcionado')
                  ->comment('Tipo do grafo: direcionado ou não-direcionado');
            
            // Métricas e estatísticas
            $table->integer('quantidade_nos')->default(0)->comment('Número total de nós no grafo');
            
            // Configurações de visualização (JSON)
            $table->json('configuracoes_visuais')->nullable()->comment('Configurações de exibição e cores');
            $table->json('configuracoes_layout')->nullable()->comment('Configurações de layout e posicionamento');
            
            // Campos de auditoria
            $table->timestamps();
            
            // Índices para performance
            $table->index(['tipo'], 'idx_grafos_tipo');
            $table->index(['created_at'], 'idx_grafos_criacao');
            $table->index(['quantidade_nos'], 'idx_grafos_quantidade_nos');
        });
        
        // SQLite não suporta comentários de tabela
        // Comentário: Tabela principal para armazenamento dos grafos e suas propriedades
    }

    /**
     * Reverte a migração - Remover tabela grafos
     */
    public function down(): void
    {
        Schema::dropIfExists('grafos');
    }
};
