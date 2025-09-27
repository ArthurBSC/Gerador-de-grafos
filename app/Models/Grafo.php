<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modelo Grafo - Representa um grafo no sistema
 * 
 * Responsabilidades:
 * - Gerenciar propriedades básicas do grafo
 * - Relacionamentos com nós e arestas
 * - Operações de cálculo (matriz de adjacência, etc.)
 */
class Grafo extends Model
{
    protected $table = 'grafos';

    protected $fillable = [
        'nome',
        'descricao',
        'tipo',
        'quantidade_nos',
        'configuracoes_visuais',
        'configuracoes_layout'
    ];

    protected $casts = [
        'configuracoes_visuais' => 'array',
        'configuracoes_layout' => 'array'
    ];

    public function nos(): HasMany
    {
        return $this->hasMany(NoGrafo::class, 'id_grafo');
    }

    public function arestas(): HasMany
    {
        return $this->hasMany(ArestaGrafo::class, 'id_grafo');
    }

    public function tabelaAdjacencia(): HasOne
    {
        return $this->hasOne(TabelaAdjacencia::class, 'id_grafo');
    }

    /**
     * Verifica se o grafo é direcionado
     */
    public function ehDirecionado(): bool
    {
        return $this->tipo === 'direcionado';
    }

    /**
     * Gera a matriz de adjacência do grafo
     */
    public function gerarMatrizAdjacencia(): array
    {
        $nos = $this->nos()->orderBy('id')->get();
        $quantidadeNos = $nos->count();
        $matriz = array_fill(0, $quantidadeNos, array_fill(0, $quantidadeNos, 0));

        foreach ($this->arestas as $aresta) {
            $indiceOrigem = $nos->search(fn($no) => $no->id === $aresta->id_no_origem);
            $indiceDestino = $nos->search(fn($no) => $no->id === $aresta->id_no_destino);

            if ($indiceOrigem !== false && $indiceDestino !== false) {
                $matriz[$indiceOrigem][$indiceDestino] = $aresta->peso;
                
                // Se não é direcionado, adiciona a aresta reversa
                if (!$this->ehDirecionado()) {
                    $matriz[$indiceDestino][$indiceOrigem] = $aresta->peso;
                }
            }
        }

        return $matriz;
    }

    /**
     * Retorna os rótulos dos nós ordenados
     */
    public function obterRotulosNos(): array
    {
        return $this->nos()->orderBy('id')->pluck('rotulo')->toArray();
    }

    /**
     * Calcula a densidade do grafo
     */
    public function calcularDensidade(): float
    {
        $quantidadeNos = $this->nos()->count();
        $quantidadeArestas = $this->arestas()->count();

        if ($quantidadeNos <= 1) {
            return 0.0;
        }

        $arestasMaximas = $quantidadeNos * ($quantidadeNos - 1);
        if (!$this->ehDirecionado()) {
            $arestasMaximas = $arestasMaximas / 2;
        }

        return round(($quantidadeArestas / $arestasMaximas) * 100, 2);
    }

    /**
     * Verifica se o grafo é completo
     */
    public function ehCompleto(): bool
    {
        $quantidadeNos = $this->nos()->count();
        $quantidadeArestas = $this->arestas()->count();

        if ($quantidadeNos <= 1) {
            return false;
        }

        $arestasNecessarias = $quantidadeNos * ($quantidadeNos - 1);
        if (!$this->ehDirecionado()) {
            $arestasNecessarias = $arestasNecessarias / 2;
        }

        return $quantidadeArestas >= $arestasNecessarias;
    }

    /**
     * Verifica se o grafo é conexo (versão simplificada)
     */
    public function ehConexo(): bool
    {
        // Implementação simplificada - pode ser expandida
        return $this->arestas()->count() >= ($this->nos()->count() - 1);
    }
}

