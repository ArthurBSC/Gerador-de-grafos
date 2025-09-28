# Arthur - Algoritmo Dijkstra

## O que vou explicar
Vou explicar como funciona o algoritmo de Dijkstra e mostrar nosso código implementado em PHP.

**Tempo**: 8 minutos

## 1. Introdução (1 minuto)

**O que falar:**
"Olha, vou explicar como funciona o algoritmo de Dijkstra. É tipo um GPS para grafos - ele encontra o caminho mais curto entre dois pontos. Nosso código tem apenas 300 linhas e está totalmente comentado."

**Pontos importantes:**
- Sempre escolhe o caminho que parece melhor (algoritmo guloso)
- Funciona só com pesos positivos
- Tempo cresce com o quadrado do número de nós
- Implementação simples e eficiente

---

## 2. Como funciona (2 minutos)

**O que preciso - 4 estruturas de dados:**
```php
$distancia = [];      // Menor distância para cada ponto
$anterior = [];       // Ponto anterior no caminho  
$visitados = [];      // Pontos já analisados
$fila = [];          // Lista de pontos para analisar
```

**Passo a passo do algoritmo:**
1. **Inicializar**: ponto inicial = 0, outros = infinito
2. **Escolher**: pegar o ponto com menor distância da fila
3. **Relaxar**: verificar se consigo chegar nos vizinhos por um caminho melhor
4. **Atualizar**: se encontrou caminho melhor, atualizar distância e anterior
5. **Repetir**: continuar até analisar todos os pontos ou chegar ao destino
6. **Reconstruir**: construir o caminho do destino até a origem

---

## 3. Nosso código explicado (4 minutos)

**Vou mostrar**: `app/Services/DijkstraService.php` - **300 linhas totalmente comentadas**

### **ETAPA 1: Preparar os dados**
```php
// Buscar todos os nós do grafo e organizar por ID
$nos = $grafo->nos->keyBy('id');

// Buscar todas as arestas (conexões) do grafo  
$arestas = $grafo->arestas;
```

### **ETAPA 2: Inicializar estruturas de dados**
```php
// $distancia = array que guarda a menor distância até cada nó
$distancia = [];

// $anterior = array que guarda qual nó veio antes de cada nó no caminho
$anterior = [];

// $visitados = array que marca quais nós já foram processados
$visitados = [];
```

### **ETAPA 3: Configurar distâncias iniciais**
```php
// Para cada nó no grafo, definir distância inicial
foreach ($nos as $id => $no) {
    // Se for o nó de origem, distância é 0 (já estamos lá)
    // Se não for origem, distância é infinito (ainda não sabemos como chegar)
    $distancia[$id] = $id === $origem ? 0 : PHP_FLOAT_MAX;
    
    // Inicialmente, nenhum nó tem um nó anterior
    $anterior[$id] = null;
}

// Criar fila de prioridade começando com o nó de origem
$fila = [$origem => 0];
```

### **ETAPA 4: Algoritmo principal de Dijkstra**
```php
// Enquanto houver nós na fila para processar
while (!empty($fila)) {
    // Encontrar o nó com menor distância na fila
    $atual = array_keys($fila, min($fila))[0];
    
    // Remover o nó atual da fila (já vamos processá-lo)
    unset($fila[$atual]);
    
    // Marcar o nó atual como visitado
    $visitados[$atual] = true;
    
    // Se chegamos ao destino, podemos parar
    if ($atual === $destino) break;
    
    // Para cada aresta (conexão) no grafo
    foreach ($arestas as $aresta) {
        // Verificar se a aresta sai do nó atual e vai para um nó não visitado
        if ($aresta->id_no_origem == $atual && !isset($visitados[$aresta->id_no_destino])) {
            
            // Calcular nova distância: distância atual + peso da aresta
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
```

### **ETAPA 5: Reconstruir o caminho**
```php
// Criar array para guardar o caminho final
$caminho = [];

// Começar pelo nó de destino
$atual = $destino;

// Voltar pelo caminho até chegar na origem
while ($atual !== null) {
    // Adicionar o nó atual no início do caminho
    array_unshift($caminho, $atual);
    
    // Ir para o nó anterior no caminho
    $atual = $anterior[$atual];
}
```

### **ETAPA 6: Retornar resultado formatado**
```php
return [
    'caminho' => $caminho,                    // Array com IDs dos nós no caminho
    'distancia' => $distancia[$destino],     // Distância total até o destino
    'detalhes' => [
        'origem' => $nos[$origem]->rotulo ?? "Nó $origem",      // Nome do nó de origem
        'destino' => $nos[$destino]->rotulo ?? "Nó $destino",   // Nome do nó de destino
        'total_nos' => count($caminho)                          // Quantos nós tem no caminho
    ]
];
```

**O que nosso código faz:**
- **Prepara**: busca nós e arestas do grafo
- **Inicializa**: cria estruturas de dados necessárias
- **Configura**: define distâncias iniciais (origem=0, outros=∞)
- **Executa**: algoritmo principal escolhendo sempre o menor
- **Relaxa**: atualiza distâncias quando encontra caminho melhor
- **Reconstrói**: monta o caminho final do destino até origem

---

## 4. Exemplo prático passo a passo (1 minuto)

**Grafo de exemplo:**
```
A --4-- B --5-- D
|       |       |
2       1       2
|       |       |
C --3-- E --1-- F
```

**Execução detalhada:**
1. **Inicialização**: A=0, B=∞, C=∞, D=∞, E=∞, F=∞
2. **Processar A**: B=4, C=2 (atualizar distâncias)
3. **Processar C**: E=5 (C+3=2+3=5)
4. **Processar B**: D=9 (B+5=4+5=9), E=5 (B+1=4+1=5, mas já temos E=5)
5. **Processar E**: F=6 (E+1=5+1=6)
6. **Resultado**: A→C→E→F, distância=6

**Por que A→C→E→F e não A→B→E→F?**
- A→B→E→F = 4+1+1 = 6 (mesma distância)
- A→C→E→F = 2+3+1 = 6 (mesma distância)
- Ambos são ótimos!

---

## 5. Perguntas que podem fazer

**P: "Por que não funciona com pesos negativos?"**
**R:** "Com pesos negativos, o algoritmo pode escolher um caminho que parece melhor, mas na verdade não é. Ele não consegue voltar atrás para reconsiderar. Por isso usamos apenas pesos positivos."

**P: "Como vocês implementaram a fila de prioridade?"**
**R:** "Usamos um array simples do PHP. A chave é o ID do nó e o valor é a distância. Para encontrar o mínimo, usamos `min()` do PHP. É simples mas eficiente para grafos pequenos."

**P: "Qual a complexidade do algoritmo?"**
**R:** "O(V²) onde V é o número de vértices. Usamos array simples, mas funciona bem para grafos pequenos. Para grafos maiores, usaríamos uma heap binária."

**P: "Por que vocês param quando chegam ao destino?"**
**R:** "Porque já encontramos o caminho mínimo até o destino. Não precisamos processar todos os outros nós, economizando tempo."

**P: "Como vocês garantem que o caminho é realmente mínimo?"**
**R:** "O algoritmo sempre escolhe o nó com menor distância conhecida. Isso garante que quando chegamos ao destino, já temos o caminho mínimo."

---

## 6. Dados de exemplo para demonstração
```
Nós: A, B, C, D, E, F
Arestas:
- A → B (peso: 4)
- A → C (peso: 2)  
- B → D (peso: 5)
- B → E (peso: 1)
- C → E (peso: 3)
- D → F (peso: 2)
- E → F (peso: 1)
```

**Resultado esperado**: A→C→E→F, distância=6

**Outros caminhos possíveis**:
- A→B→E→F = 4+1+1 = 6 (também ótimo)
- A→B→D→F = 4+5+2 = 11 (não ótimo)
