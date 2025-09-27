<?php

namespace App\Services;

use App\Models\Grafo;
use Illuminate\Support\Facades\Log;

/**
 * Serviço responsável pelo algoritmo de Dijkstra
 * 
 * Responsabilidades:
 * - Calcular caminho mínimo entre dois nós
 * - Implementar algoritmo de Dijkstra
 * - Retornar resultados formatados
 */
class DijkstraService
{
    /**
     * Calcula o caminho mínimo entre dois nós usando algoritmo de Dijkstra
     */
    public function calcularCaminhoMinimo(Grafo $grafo, int $origem, int $destino): array
    {
        $nos = $grafo->nos->keyBy('id');
        $arestas = $grafo->arestas;
        
        $distancia = [];
        $anterior = [];
        $visitados = [];
        
        // Inicializar distâncias
        foreach ($nos as $id => $no) {
            $distancia[$id] = $id === $origem ? 0 : PHP_FLOAT_MAX;
            $anterior[$id] = null;
        }
        
        $fila = [$origem => 0];
        
        while (!empty($fila)) {
            $atual = array_keys($fila, min($fila))[0];
            unset($fila[$atual]);
            $visitados[$atual] = true;
            
            if ($atual === $destino) break;
            
            // Verificar arestas saindo do nó atual
            foreach ($arestas as $aresta) {
                if ($aresta->id_no_origem == $atual && !isset($visitados[$aresta->id_no_destino])) {
                    $novaDistancia = $distancia[$atual] + abs($aresta->peso);
                    
                    if ($novaDistancia < $distancia[$aresta->id_no_destino]) {
                        $distancia[$aresta->id_no_destino] = $novaDistancia;
                        $anterior[$aresta->id_no_destino] = $atual;
                        $fila[$aresta->id_no_destino] = $novaDistancia;
                    }
                }
            }
        }
        
        // Reconstruir caminho
        $caminho = [];
        $atual = $destino;
        
        while ($atual !== null) {
            array_unshift($caminho, $atual);
            $atual = $anterior[$atual];
        }
        
        return [
            'caminho' => $caminho,
            'distancia' => $distancia[$destino],
            'detalhes' => [
                'origem' => $nos[$origem]->rotulo ?? "Nó $origem",
                'destino' => $nos[$destino]->rotulo ?? "Nó $destino",
                'total_nos' => count($caminho)
            ]
        ];
    }

    /**
     * Verifica se existe caminho entre dois nós
     */
    public function existeCaminho(Grafo $grafo, int $origem, int $destino): bool
    {
        $resultado = $this->calcularCaminhoMinimo($grafo, $origem, $destino);
        return $resultado['distancia'] !== PHP_FLOAT_MAX;
    }

    /**
     * Calcula todos os caminhos mínimos a partir de um nó (algoritmo de Dijkstra completo)
     */
    public function calcularTodosCaminhosMinimos(Grafo $grafo, int $origem): array
    {
        $nos = $grafo->nos->keyBy('id');
        $arestas = $grafo->arestas;
        
        $distancia = [];
        $anterior = [];
        $visitados = [];
        
        // Inicializar distâncias
        foreach ($nos as $id => $no) {
            $distancia[$id] = $id === $origem ? 0 : PHP_FLOAT_MAX;
            $anterior[$id] = null;
        }
        
        $fila = [$origem => 0];
        
        while (!empty($fila)) {
            $atual = array_keys($fila, min($fila))[0];
            unset($fila[$atual]);
            $visitados[$atual] = true;
            
            // Verificar arestas saindo do nó atual
            foreach ($arestas as $aresta) {
                if ($aresta->id_no_origem == $atual && !isset($visitados[$aresta->id_no_destino])) {
                    $novaDistancia = $distancia[$atual] + abs($aresta->peso);
                    
                    if ($novaDistancia < $distancia[$aresta->id_no_destino]) {
                        $distancia[$aresta->id_no_destino] = $novaDistancia;
                        $anterior[$aresta->id_no_destino] = $atual;
                        $fila[$aresta->id_no_destino] = $novaDistancia;
                    }
                }
            }
        }
        
        // Reconstruir todos os caminhos
        $caminhos = [];
        foreach ($nos as $id => $no) {
            if ($id !== $origem) {
                $caminho = [];
                $atual = $id;
                
                while ($atual !== null) {
                    array_unshift($caminho, $atual);
                    $atual = $anterior[$atual];
                }
                
                $caminhos[$id] = [
                    'caminho' => $caminho,
                    'distancia' => $distancia[$id],
                    'destino' => $no->rotulo ?? "Nó $id"
                ];
            }
        }
        
        return $caminhos;
    }
}

