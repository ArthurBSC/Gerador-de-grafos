# Arthur - Algoritmo Dijkstra

## O que vou explicar
Vou explicar como funciona o algoritmo de Dijkstra e mostrar nosso código.

**Tempo**: 7 minutos

## 1. Introdução (1 minuto)

**O que falar:**
"Olha, vou explicar como funciona o algoritmo de Dijkstra. É tipo um GPS para grafos - ele encontra o caminho mais curto entre dois pontos."

**Pontos importantes:**
- Sempre escolhe o caminho que parece melhor
- Funciona só com pesos positivos
- Tempo cresce com o quadrado do número de nós

---

## 2. Como funciona (2 minutos)

**O que preciso:**
```php
$distancia = [];      // Menor distância para cada ponto
$anterior = [];       // Ponto anterior no caminho
$visitados = [];      // Pontos já analisados
$fila = [];          // Lista de pontos para analisar
```

**Passo a passo:**
1. Começar: ponto inicial = 0, outros = infinito
2. Escolher: pegar o ponto com menor distância
3. Atualizar: verificar se consigo chegar nos vizinhos por um caminho melhor
4. Repetir: continuar até analisar todos os pontos
5. Montar: construir o caminho do destino até a origem

---

## 3. Nosso código (3 minutos)

**Vou mostrar**: `app/Services/DijkstraService.php`

```php
public function calcularCaminhoMinimo(Grafo $grafo, int $origem, int $destino): array
{
    $nos = $grafo->nos->keyBy('id');
    $arestas = $grafo->arestas;
    
    // Estruturas de dados para o algoritmo
    $distancia = [];
    $anterior = [];
    $visitados = [];
    
    // Inicializar distâncias
    foreach ($nos as $id => $no) {
        $distancia[$id] = $id === $origem ? 0 : PHP_FLOAT_MAX;
        $anterior[$id] = null;
    }
    
    $fila = [$origem => 0];
    
    // Algoritmo principal
    while (!empty($fila)) {
        // Encontrar nó com menor distância
        $atual = array_keys($fila, min($fila))[0];
        unset($fila[$atual]);
        $visitados[$atual] = true;
        
        // Parar se chegou ao destino
        if ($atual === $destino) break;
        
        // Relaxar arestas saindo do nó atual
        foreach ($arestas as $aresta) {
            if ($aresta->id_no_origem == $atual && 
                !isset($visitados[$aresta->id_no_destino])) {
                
                $novaDistancia = $distancia[$atual] + abs($aresta->peso);
                
                // Se encontrou caminho melhor, atualizar
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
```

**O que nosso código faz:**
- Começamos: ponto inicial = 0, outros = infinito
- Escolhemos: sempre o ponto com menor distância
- Atualizamos: distâncias quando encontramos caminho melhor
- Paramos: quando chegamos ao destino
- Montamos: o caminho usando os pontos anteriores

---

## 4. Exemplo prático (1 minuto)

**Grafo de exemplo:**
```
A --4-- B --5-- D
|       |       |
2       1       2
|       |       |
C --3-- E --1-- F
```

**Como executa:**
1. Inicialização: A=0, outros=∞
2. Processar A: B=4, C=2
3. Processar C: E=5
4. Processar B: D=9, E=5 (melhor)
5. Processar E: F=6
6. Resultado: A→C→E→F, distância=6

## Perguntas que podem fazer

**P: "Por que não funciona com pesos negativos?"**
**R:** "Com pesos negativos, o algoritmo pode escolher um caminho que parece melhor, mas na verdade não é. Ele não consegue voltar atrás para reconsiderar."

**P: "Como vocês implementaram a fila de prioridade?"**
**R:** "Usamos um array simples. A chave é o ID do nó e o valor é a distância. Para encontrar o mínimo, usamos `min()` do PHP."

**P: "Qual a complexidade?"**
**R:** "O(V²) onde V é o número de vértices. Usamos array simples, mas funciona bem para grafos pequenos."

## Dados de exemplo
```
Nós: A, B, C, D, E, F
Arestas:
- A → B (peso: 4)
- A → C (peso: 2)
- B → D (peso: 5)
- C → D (peso: 1)
- C → E (peso: 3)
- D → F (peso: 2)
- E → F (peso: 1)
```

**Resultado**: A→C→E→F, distância=6
