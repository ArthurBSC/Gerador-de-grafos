<?php

namespace App\Services;

use App\Models\Grafo;
use Illuminate\Support\Facades\Log;

/**
 * SERVIÇO DIJKSTRA - ALGORITMO DE CAMINHO MÍNIMO
 * 
 * Este arquivo implementa o famoso algoritmo de Dijkstra para encontrar
 * o caminho mais curto entre dois pontos em um grafo.
 * 
 * COMO FUNCIONA:
 * 1. Começa no nó de origem com distância 0
 * 2. Visita todos os vizinhos e calcula distâncias
 * 3. Sempre escolhe o nó com menor distância não visitado
 * 4. Repete até chegar ao destino
 * 5. Reconstrói o caminho de volta
 */
class DijkstraService
{
    /**
     * MÉTODO PRINCIPAL - CALCULA CAMINHO MÍNIMO ENTRE DOIS NÓS
     * 
     * Parâmetros:
     * - $grafo: Objeto que contém todos os nós e arestas
     * - $origem: ID do nó onde começamos (número inteiro)
     * - $destino: ID do nó onde queremos chegar (número inteiro)
     * 
     * Retorna: Array com o caminho, distância e detalhes
     */
    public function calcularCaminhoMinimo(Grafo $grafo, int $origem, int $destino): array
    {
        // ========================================
        // ETAPA 1: PREPARAR OS DADOS
        // ========================================
        
        // Buscar todos os nós do grafo e organizar por ID
        // $nos = array onde cada chave é o ID do nó e o valor é o objeto nó
        $nos = $grafo->nos->keyBy('id');
        
        // Buscar todas as arestas (conexões) do grafo
        // $arestas = array com todas as conexões entre nós
        $arestas = $grafo->arestas;
        
        // ========================================
        // ETAPA 2: INICIALIZAR ESTRUTURAS DE DADOS
        // ========================================
        
        // $distancia = array que guarda a menor distância até cada nó
        // Exemplo: $distancia[1] = 5 significa que a distância até o nó 1 é 5
        $distancia = [];
        
        // $anterior = array que guarda qual nó veio antes de cada nó no caminho
        // Exemplo: $anterior[3] = 1 significa que para chegar no nó 3, veio do nó 1
        $anterior = [];
        
        // $visitados = array que marca quais nós já foram processados
        // Exemplo: $visitados[2] = true significa que o nó 2 já foi visitado
        $visitados = [];
        
        // ========================================
        // ETAPA 3: CONFIGURAR DISTÂNCIAS INICIAIS
        // ========================================
        
        // Para cada nó no grafo, definir distância inicial
        foreach ($nos as $id => $no) {
            // Se for o nó de origem, distância é 0 (já estamos lá)
            // Se não for origem, distância é infinito (ainda não sabemos como chegar)
            $distancia[$id] = $id === $origem ? 0 : PHP_FLOAT_MAX;
            
            // Inicialmente, nenhum nó tem um nó anterior
            $anterior[$id] = null;
        }
        
        // Criar fila de prioridade começando com o nó de origem
        // $fila = array onde a chave é o ID do nó e o valor é a distância
        $fila = [$origem => 0];
        
        // ========================================
        // ETAPA 4: ALGORITMO PRINCIPAL DE DIJKSTRA
        // ========================================
        
        // Enquanto houver nós na fila para processar
        while (!empty($fila)) {
            // Encontrar o nó com menor distância na fila
            // array_keys($fila, min($fila))[0] = pega a chave do menor valor
            $atual = array_keys($fila, min($fila))[0];
            
            // Remover o nó atual da fila (já vamos processá-lo)
            unset($fila[$atual]);
            
            // Marcar o nó atual como visitado
            $visitados[$atual] = true;
            
            // Se chegamos ao destino, podemos parar
            if ($atual === $destino) break;
            
            // ========================================
            // ETAPA 5: VERIFICAR VIZINHOS DO NÓ ATUAL
            // ========================================
            
            // Para cada aresta (conexão) no grafo
            foreach ($arestas as $aresta) {
                // Verificar se a aresta sai do nó atual e vai para um nó não visitado
                if ($aresta->id_no_origem == $atual && !isset($visitados[$aresta->id_no_destino])) {
                    
                    // Calcular nova distância: distância atual + peso da aresta
                    // abs() = valor absoluto (sempre positivo)
                    $novaDistancia = $distancia[$atual] + abs($aresta->peso);
                    
                    // Se a nova distância for menor que a distância conhecida
                    if ($novaDistancia < $distancia[$aresta->id_no_destino]) {
                        // Atualizar a distância para o nó de destino
                        $distancia[$aresta->id_no_destino] = $novaDistancia;
                        
                        // Marcar que para chegar neste nó, veio do nó atual
                        $anterior[$aresta->id_no_destino] = $atual;
                        
                        // Adicionar o nó de destino na fila para processar depois
                        $fila[$aresta->id_no_destino] = $novaDistancia;
                    }
                }
            }
        }
        
        // ========================================
        // ETAPA 6: RECONSTRUIR O CAMINHO
        // ========================================
        
        // Criar array para guardar o caminho final
        $caminho = [];
        
        // Começar pelo nó de destino
        $atual = $destino;
        
        // Voltar pelo caminho até chegar na origem
        while ($atual !== null) {
            // Adicionar o nó atual no início do caminho
            // array_unshift() = adiciona no início do array
            array_unshift($caminho, $atual);
            
            // Ir para o nó anterior no caminho
            $atual = $anterior[$atual];
        }
        
        // ========================================
        // ETAPA 7: RETORNAR RESULTADO FORMATADO
        // ========================================
        
        return [
            'caminho' => $caminho,                    // Array com IDs dos nós no caminho
            'distancia' => $distancia[$destino],     // Distância total até o destino
            'detalhes' => [
                'origem' => $nos[$origem]->rotulo ?? "Nó $origem",      // Nome do nó de origem
                'destino' => $nos[$destino]->rotulo ?? "Nó $destino",   // Nome do nó de destino
                'total_nos' => count($caminho)                          // Quantos nós tem no caminho
            ]
        ];
    }

    /**
     * MÉTODO AUXILIAR - VERIFICA SE EXISTE CAMINHO ENTRE DOIS NÓS
     * 
     * Este método é mais simples e só quer saber se é possível chegar
     * do nó origem até o nó destino, sem se preocupar com o caminho.
     * 
     * Parâmetros:
     * - $grafo: Objeto que contém todos os nós e arestas
     * - $origem: ID do nó onde começamos
     * - $destino: ID do nó onde queremos chegar
     * 
     * Retorna: true se existe caminho, false se não existe
     */
    public function existeCaminho(Grafo $grafo, int $origem, int $destino): bool
    {
        // Usar o método principal para calcular o caminho
        $resultado = $this->calcularCaminhoMinimo($grafo, $origem, $destino);
        
        // Se a distância for PHP_FLOAT_MAX (infinito), significa que não há caminho
        // Se for um número normal, significa que existe caminho
        return $resultado['distancia'] !== PHP_FLOAT_MAX;
    }

    /**
     * MÉTODO AVANÇADO - CALCULA TODOS OS CAMINHOS MÍNIMOS A PARTIR DE UM NÓ
     * 
     * Este método é uma versão completa do algoritmo de Dijkstra que calcula
     * o caminho mínimo do nó origem para TODOS os outros nós do grafo.
     * 
     * É útil quando você quer saber a distância de um ponto para todos os outros.
     * Por exemplo: "Qual a distância do nó A para todos os outros nós?"
     * 
     * Parâmetros:
     * - $grafo: Objeto que contém todos os nós e arestas
     * - $origem: ID do nó de onde queremos calcular todas as distâncias
     * 
     * Retorna: Array com todos os caminhos e distâncias
     */
    public function calcularTodosCaminhosMinimos(Grafo $grafo, int $origem): array
    {
        // ========================================
        // ETAPA 1: PREPARAR OS DADOS (igual ao método anterior)
        // ========================================
        
        // Buscar todos os nós e organizar por ID
        $nos = $grafo->nos->keyBy('id');
        
        // Buscar todas as arestas
        $arestas = $grafo->arestas;
        
        // ========================================
        // ETAPA 2: INICIALIZAR ESTRUTURAS (igual ao método anterior)
        // ========================================
        
        // Arrays para guardar distâncias, nós anteriores e nós visitados
        $distancia = [];
        $anterior = [];
        $visitados = [];
        
        // Configurar distâncias iniciais
        foreach ($nos as $id => $no) {
            // Nó origem tem distância 0, outros têm distância infinita
            $distancia[$id] = $id === $origem ? 0 : PHP_FLOAT_MAX;
            
            // Inicialmente nenhum nó tem anterior
            $anterior[$id] = null;
        }
        
        // Começar com o nó origem na fila
        $fila = [$origem => 0];
        
        // ========================================
        // ETAPA 3: ALGORITMO DE DIJKSTRA COMPLETO
        // ========================================
        
        // Processar todos os nós até esgotar a fila
        while (!empty($fila)) {
            // Pegar o nó com menor distância
            $atual = array_keys($fila, min($fila))[0];
            
            // Remover da fila e marcar como visitado
            unset($fila[$atual]);
            $visitados[$atual] = true;
            
            // Verificar todos os vizinhos do nó atual
            foreach ($arestas as $aresta) {
                // Se a aresta sai do nó atual e vai para um não visitado
                if ($aresta->id_no_origem == $atual && !isset($visitados[$aresta->id_no_destino])) {
                    
                    // Calcular nova distância
                    $novaDistancia = $distancia[$atual] + abs($aresta->peso);
                    
                    // Se for melhor que a distância conhecida, atualizar
                    if ($novaDistancia < $distancia[$aresta->id_no_destino]) {
                        $distancia[$aresta->id_no_destino] = $novaDistancia;
                        $anterior[$aresta->id_no_destino] = $atual;
                        $fila[$aresta->id_no_destino] = $novaDistancia;
                    }
                }
            }
        }
        
        // ========================================
        // ETAPA 4: RECONSTRUIR TODOS OS CAMINHOS
        // ========================================
        
        // Array para guardar todos os caminhos calculados
        $caminhos = [];
        
        // Para cada nó (exceto o origem), reconstruir o caminho
        foreach ($nos as $id => $no) {
            // Não calcular caminho para o próprio nó origem
            if ($id !== $origem) {
                
                // Reconstruir caminho deste nó até a origem
                $caminho = [];
                $atual = $id;
                
                // Voltar pelo caminho até chegar na origem
                while ($atual !== null) {
                    array_unshift($caminho, $atual);
                    $atual = $anterior[$atual];
                }
                
                // Guardar informações deste caminho
                $caminhos[$id] = [
                    'caminho' => $caminho,                    // Array com IDs dos nós no caminho
                    'distancia' => $distancia[$id],           // Distância total até este nó
                    'destino' => $no->rotulo ?? "Nó $id"      // Nome do nó de destino
                ];
            }
        }
        
        // Retornar todos os caminhos calculados
        return $caminhos;
    }
}

