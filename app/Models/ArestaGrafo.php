<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo ArestaGrafo - Representa uma aresta/ligação entre dois nós
 * 
 * Responsabilidades:
 * - Gerenciar propriedades da aresta (peso, cor, largura)
 * - Relacionamentos com grafo e nós
 * - Validações e operações específicas de arestas
 */
class ArestaGrafo extends Model
{
    protected $table = 'arestas_grafo';

    protected $fillable = [
        'id_grafo',
        'id_no_origem',
        'id_no_destino',
        'peso',
        'cor',
        'largura',
        'propriedades'
    ];

    protected $casts = [
        'propriedades' => 'array'
    ];

    public function grafo(): BelongsTo
    {
        return $this->belongsTo(Grafo::class, 'id_grafo');
    }

    public function noOrigem(): BelongsTo
    {
        return $this->belongsTo(NoGrafo::class, 'id_no_origem');
    }

    public function noDestino(): BelongsTo
    {
        return $this->belongsTo(NoGrafo::class, 'id_no_destino');
    }

    /**
     * Verifica se a aresta é um laço (conecta um nó a ele mesmo)
     */
    public function ehLaco(): bool
    {
        return $this->id_no_origem === $this->id_no_destino;
    }

    /**
     * Retorna a representação textual da aresta
     */
    public function obterRotulo(): string
    {
        $origem = $this->noOrigem->rotulo ?? "N{$this->id_no_origem}";
        $destino = $this->noDestino->rotulo ?? "N{$this->id_no_destino}";
        
        $seta = $this->grafo->ehDirecionado() ? ' → ' : ' ↔ ';
        
        return $origem . $seta . $destino . ($this->peso !== 1 ? " ({$this->peso})" : '');
    }

    /**
     * Verifica se existe uma aresta reversa (para grafos não direcionados)
     */
    public function possuiArestaReversa(): bool
    {
        if ($this->grafo->ehDirecionado()) {
            return false;
        }

        return self::where('id_grafo', $this->id_grafo)
                   ->where('id_no_origem', $this->id_no_destino)
                   ->where('id_no_destino', $this->id_no_origem)
                   ->exists();
    }

    /**
     * Retorna a cor baseada no peso da aresta
     */
    public function obterCorPorPeso(): string
    {
        return match($this->peso) {
            1 => '#2ecc71',   // verde para peso positivo
            -1 => '#e74c3c',  // vermelho para peso negativo
            0 => '#95a5a6',   // cinza para peso zero
            default => $this->cor ?? '#3498db'
        };
    }

    /**
     * Retorna o tipo de relação baseado no peso
     */
    public function obterTipoRelacao(): string
    {
        return match($this->peso) {
            1 => 'positiva',
            -1 => 'negativa',
            0 => 'neutra',
            default => 'personalizada'
        };
    }

    /**
     * Verifica se a aresta é válida (não conecta um nó inexistente)
     */
    public function ehValida(): bool
    {
        return $this->noOrigem !== null && $this->noDestino !== null;
    }

    /**
     * Calcula o comprimento visual da aresta
     */
    public function calcularComprimentoVisual(): float
    {
        if (!$this->ehValida()) {
            return 0;
        }

        return $this->noOrigem->calcularDistanciaVisual($this->noDestino);
    }

    /**
     * Verifica se a aresta é paralela a outra (mesma origem e destino)
     */
    public function ehParalelaA(ArestaGrafo $outraAresta): bool
    {
        return ($this->id_no_origem === $outraAresta->id_no_origem &&
                $this->id_no_destino === $outraAresta->id_no_destino) ||
               (!$this->grafo->ehDirecionado() &&
                $this->id_no_origem === $outraAresta->id_no_destino &&
                $this->id_no_destino === $outraAresta->id_no_origem);
    }

    /**
     * Retorna representação textual da aresta para debug
     */
    public function __toString(): string
    {
        return "Aresta {$this->obterRotulo()} (Peso: {$this->peso}, Tipo: {$this->obterTipoRelacao()})";
    }
}
