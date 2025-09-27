<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo NoGrafo - Representa um nó/vértice em um grafo
 * 
 * Responsabilidades:
 * - Gerenciar propriedades do nó (posição, cor, tamanho)
 * - Relacionamentos com grafo e arestas
 * - Cálculos de grau e adjacências
 */
class NoGrafo extends Model
{
    protected $table = 'nos_grafo';

    protected $fillable = [
        'id_grafo',
        'rotulo',
        'posicao_x',
        'posicao_y',
        'cor',
        'tamanho',
        'propriedades'
    ];

    protected $casts = [
        'propriedades' => 'array'
    ];

    public function grafo(): BelongsTo
    {
        return $this->belongsTo(Grafo::class, 'id_grafo');
    }

    public function arestasOrigem(): HasMany
    {
        return $this->hasMany(ArestaGrafo::class, 'id_no_origem');
    }

    public function arestasDestino(): HasMany
    {
        return $this->hasMany(ArestaGrafo::class, 'id_no_destino');
    }

    /**
     * Retorna todas as arestas conectadas a este nó
     */
    public function arestas()
    {
        return ArestaGrafo::where('id_no_origem', $this->id)
                         ->orWhere('id_no_destino', $this->id);
    }

    /**
     * Calcula o grau total do nó
     */
    public function calcularGrau(): int
    {
        if ($this->grafo->ehDirecionado()) {
            return $this->calcularGrauEntrada() + $this->calcularGrauSaida();
        }
        
        return $this->arestas()->count();
    }

    /**
     * Calcula o grau de entrada (para grafos direcionados)
     */
    public function calcularGrauEntrada(): int
    {
        return $this->arestasDestino()->count();
    }

    /**
     * Calcula o grau de saída (para grafos direcionados)
     */
    public function calcularGrauSaida(): int
    {
        return $this->arestasOrigem()->count();
    }

    /**
     * Retorna os nós adjacentes a este nó
     */
    public function obterNosAdjacentes()
    {
        $idsAdjacentes = collect();
        
        // Nós conectados por arestas de saída
        $idsAdjacentes = $idsAdjacentes->merge(
            $this->arestasOrigem()->pluck('id_no_destino')
        );

        // Para grafos não direcionados, inclui nós conectados por arestas de entrada
        if (!$this->grafo->ehDirecionado()) {
            $idsAdjacentes = $idsAdjacentes->merge(
                $this->arestasDestino()->pluck('id_no_origem')
            );
        }

        return NoGrafo::whereIn('id', $idsAdjacentes->unique())->get();
    }

    /**
     * Verifica se este nó está conectado a outro nó específico
     */
    public function estaConectadoA(NoGrafo $outroNo): bool
    {
        return $this->obterNosAdjacentes()->contains('id', $outroNo->id);
    }

    /**
     * Retorna a distância visual entre dois nós (para posicionamento)
     */
    public function calcularDistanciaVisual(NoGrafo $outroNo): float
    {
        $deltaX = $this->posicao_x - $outroNo->posicao_x;
        $deltaY = $this->posicao_y - $outroNo->posicao_y;
        
        return sqrt($deltaX * $deltaX + $deltaY * $deltaY);
    }

    /**
     * Verifica se o nó é isolado (sem conexões)
     */
    public function ehIsolado(): bool
    {
        return $this->calcularGrau() === 0;
    }

    /**
     * Retorna representação textual do nó para debug
     */
    public function __toString(): string
    {
        return "Nó {$this->rotulo} (ID: {$this->id}, Grau: {$this->calcularGrau()})";
    }
}

