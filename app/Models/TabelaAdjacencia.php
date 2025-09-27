<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo TabelaAdjacencia - Representa a matriz de adjacência de um grafo
 * 
 * Responsabilidades:
 * - Armazenar matriz de adjacência
 * - Operações de consulta e manipulação da matriz
 * - Exportação em diferentes formatos
 */
class TabelaAdjacencia extends Model
{
    protected $table = 'tabelas_adjacencia';

    protected $fillable = [
        'id_grafo',
        'nome',
        'tipo',
        'matriz',
        'rotulos_linhas',
        'rotulos_colunas'
    ];

    protected $casts = [
        'matriz' => 'array',
        'rotulos_linhas' => 'array',
        'rotulos_colunas' => 'array'
    ];

    public function grafo(): BelongsTo
    {
        return $this->belongsTo(Grafo::class, 'id_grafo');
    }

    /**
     * Verifica se a matriz é quadrada
     */
    public function ehMatrizQuadrada(): bool
    {
        if (empty($this->matriz)) {
            return false;
        }

        $numeroLinhas = count($this->matriz);
        
        foreach ($this->matriz as $linha) {
            if (count($linha) !== $numeroLinhas) {
                return false;
            }
        }

        return true;
    }

    /**
     * Retorna o valor em uma posição específica da matriz
     */
    public function obterValor(int $linha, int $coluna): int
    {
        if (!isset($this->matriz[$linha][$coluna])) {
            return 0;
        }

        return $this->matriz[$linha][$coluna];
    }

    /**
     * Define um valor em uma posição específica da matriz
     */
    public function definirValor(int $linha, int $coluna, int $valor): void
    {
        if (!isset($this->matriz[$linha])) {
            $this->matriz[$linha] = [];
        }

        $this->matriz[$linha][$coluna] = $valor;
    }

    /**
     * Retorna a dimensão da matriz (número de linhas/colunas)
     */
    public function obterDimensao(): int
    {
        return count($this->matriz);
    }

    /**
     * Verifica se a matriz é simétrica (grafo não direcionado)
     */
    public function ehSimetrica(): bool
    {
        if (!$this->ehMatrizQuadrada()) {
            return false;
        }

        $dimensao = $this->obterDimensao();

        for ($i = 0; $i < $dimensao; $i++) {
            for ($j = 0; $j < $dimensao; $j++) {
                if ($this->obterValor($i, $j) !== $this->obterValor($j, $i)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Calcula o grau de um nó específico
     */
    public function calcularGrauNo(int $indiceNo): int
    {
        if (!isset($this->matriz[$indiceNo])) {
            return 0;
        }

        $grau = 0;
        $dimensao = $this->obterDimensao();

        for ($j = 0; $j < $dimensao; $j++) {
            $grau += $this->obterValor($indiceNo, $j);
            
            // Para grafos não direcionados, não contar duas vezes
            if ($this->ehSimetrica() && $indiceNo !== $j) {
                $grau += $this->obterValor($j, $indiceNo);
            }
        }

        return $grau;
    }

    /**
     * Exporta a matriz como array bidimensional simples
     */
    public function exportarComoArray(): array
    {
        return $this->matriz;
    }

    /**
     * Exporta a matriz como string formatada
     */
    public function exportarComoTexto(): string
    {
        if (empty($this->matriz)) {
            return 'Matriz vazia';
        }

        $texto = '';
        $dimensao = $this->obterDimensao();

        // Cabeçalho com rótulos das colunas
        if (!empty($this->rotulos_colunas)) {
            $texto .= "    ";
            foreach ($this->rotulos_colunas as $rotulo) {
                $texto .= sprintf("%4s", $rotulo);
            }
            $texto .= "\n";
        }

        // Linhas da matriz
        for ($i = 0; $i < $dimensao; $i++) {
            // Rótulo da linha
            if (!empty($this->rotulos_linhas[$i])) {
                $texto .= sprintf("%3s ", $this->rotulos_linhas[$i]);
            }

            // Valores da linha
            for ($j = 0; $j < $dimensao; $j++) {
                $texto .= sprintf("%4d", $this->obterValor($i, $j));
            }
            $texto .= "\n";
        }

        return $texto;
    }

    /**
     * Exporta a matriz em formato CSV
     */
    public function exportarComoCSV(): string
    {
        if (empty($this->matriz)) {
            return '';
        }

        $csv = '';
        $dimensao = $this->obterDimensao();

        // Cabeçalho com rótulos
        if (!empty($this->rotulos_colunas)) {
            $csv .= ',' . implode(',', $this->rotulos_colunas) . "\n";
        }

        // Linhas da matriz
        for ($i = 0; $i < $dimensao; $i++) {
            $linha = [];
            
            // Rótulo da linha
            if (!empty($this->rotulos_linhas[$i])) {
                $linha[] = $this->rotulos_linhas[$i];
            }

            // Valores da linha
            for ($j = 0; $j < $dimensao; $j++) {
                $linha[] = $this->obterValor($i, $j);
            }

            $csv .= implode(',', $linha) . "\n";
        }

        return $csv;
    }

    /**
     * Calcula estatísticas da matriz
     */
    public function calcularEstatisticas(): array
    {
        if (empty($this->matriz)) {
            return [
                'dimensao' => 0,
                'total_arestas' => 0,
                'densidade' => 0,
                'eh_simetrica' => false
            ];
        }

        $dimensao = $this->obterDimensao();
        $totalArestas = 0;

        for ($i = 0; $i < $dimensao; $i++) {
            for ($j = 0; $j < $dimensao; $j++) {
                if ($this->obterValor($i, $j) !== 0) {
                    $totalArestas++;
                }
            }
        }

        // Para grafos não direcionados, dividir por 2
        if ($this->ehSimetrica()) {
            $totalArestas = $totalArestas / 2;
        }

        $arestasMaximas = $dimensao * ($dimensao - 1);
        if ($this->ehSimetrica()) {
            $arestasMaximas = $arestasMaximas / 2;
        }

        $densidade = $arestasMaximas > 0 ? ($totalArestas / $arestasMaximas) * 100 : 0;

        return [
            'dimensao' => $dimensao,
            'total_arestas' => $totalArestas,
            'densidade' => round($densidade, 2),
            'eh_simetrica' => $this->ehSimetrica()
        ];
    }
}
