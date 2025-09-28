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

**O que preciso - 4 "caixinhas" para guardar informações:**
```php
$distancia = [];      // Uma lista que guarda "quão longe está cada ponto"
$anterior = [];       // Uma lista que guarda "de onde vim para chegar aqui"  
$visitados = [];      // Uma lista que marca "quais pontos já olhei"
$fila = [];          // Uma lista de "pontos que ainda preciso olhar"
```

**Explicação simples:**
- **`$distancia`** = como uma tabela: "Para ir do ponto A ao ponto B, preciso andar X metros"
- **`$anterior`** = como um GPS: "Para chegar no ponto B, vim do ponto A"
- **`$visitados`** = como uma lista de presença: "Já olhei o ponto A, ponto B, etc."
- **`$fila`** = como uma fila de banco: "Ainda preciso olhar o ponto C, ponto D, etc."

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
// Buscar todos os pontos do grafo e organizar por número
$nos = $grafo->nos->keyBy('id');

// Buscar todas as linhas (conexões) entre os pontos  
$arestas = $grafo->arestas;
```

**O que significa cada parte:**
- **`$nos`** = lista de todos os pontos (A, B, C, D, etc.)
- **`$arestas`** = lista de todas as linhas que conectam os pontos
- **`->keyBy('id')`** = organizar os pontos por número (ponto 1, ponto 2, etc.)

### **ETAPA 2: Criar as "caixinhas" para guardar informações**
```php
// Uma lista que guarda "quão longe está cada ponto"
$distancia = [];

// Uma lista que guarda "de onde vim para chegar aqui"
$anterior = [];

// Uma lista que marca "quais pontos já olhei"
$visitados = [];
```

**Explicação dos símbolos:**
- **`$`** = em PHP, sempre começa com $ (é como dizer "esta é uma variável")
- **`[]`** = significa "lista vazia" (como uma caixa vazia)
- **`$distancia`** = nome da lista (pode ser qualquer nome)

### **ETAPA 3: Configurar distâncias iniciais**
```php
// Para cada ponto no grafo, definir distância inicial
foreach ($nos as $id => $no) {
    // Se for o ponto de origem, distância é 0 (já estamos lá)
    // Se não for origem, distância é infinito (ainda não sabemos como chegar)
    $distancia[$id] = $id === $origem ? 0 : PHP_FLOAT_MAX;
    
    // Inicialmente, nenhum ponto tem um ponto anterior
    $anterior[$id] = null;
}

// Criar lista de pontos para analisar começando com o ponto de origem
$fila = [$origem => 0];
```

**O que significa cada símbolo:**
- **`foreach`** = "para cada" (como dizer "para cada ponto na lista")
- **`as $id => $no`** = "pegue o número do ponto e o ponto" (como: ponto 1, ponto 2)
- **`$distancia[$id]`** = "na lista distância, na posição do ponto"
- **`===`** = "é exatamente igual a" (comparação)
- **`?`** = "se for verdade, faça isso"
- **`:`** = "senão, faça aquilo"
- **`PHP_FLOAT_MAX`** = "número muito grande" (infinito)
- **`null`** = "nada" (vazio)

### **ETAPA 4: Algoritmo principal (o coração do Dijkstra)**
```php
// Enquanto ainda tiver pontos para analisar
while (!empty($fila)) {
    // Encontrar o ponto com menor distância na lista
    $atual = array_keys($fila, min($fila))[0];
    
    // Tirar este ponto da lista (já vamos analisá-lo)
    unset($fila[$atual]);
    
    // Marcar este ponto como "já olhei"
    $visitados[$atual] = true;
    
    // Se chegamos onde queríamos, podemos parar
    if ($atual === $destino) break;
    
    // Para cada linha (conexão) no grafo
    foreach ($arestas as $aresta) {
        // Verificar se a linha sai do ponto atual e vai para um ponto não visitado
        if ($aresta->id_no_origem == $atual && !isset($visitados[$aresta->id_no_destino])) {
            
            // Calcular nova distância: distância atual + peso da linha
            $novaDistancia = $distancia[$atual] + abs($aresta->peso);
            
            // Se a nova distância for menor que a distância conhecida
            if ($novaDistancia < $distancia[$aresta->id_no_destino]) {
                // Atualizar a distância para o ponto de destino
                $distancia[$aresta->id_no_destino] = $novaDistancia;
                
                // Marcar que para chegar neste ponto, vim do ponto atual
                $anterior[$aresta->id_no_destino] = $atual;
                
                // Adicionar o ponto de destino na lista para analisar depois
                $fila[$aresta->id_no_destino] = $novaDistancia;
            }
        }
    }
}
```

**O que significa cada símbolo:**
- **`while`** = "enquanto" (repita enquanto for verdade)
- **`!empty`** = "não está vazio" (tem alguma coisa)
- **`array_keys`** = "pegue as chaves da lista"
- **`min`** = "menor valor"
- **`unset`** = "remover da lista"
- **`isset`** = "existe na lista"
- **`abs`** = "valor absoluto" (sempre positivo)
- **`break`** = "pare aqui"

### **ETAPA 5: Montar o caminho final**
```php
// Criar uma lista para guardar o caminho final
$caminho = [];

// Começar pelo ponto de destino
$atual = $destino;

// Voltar pelo caminho até chegar na origem
while ($atual !== null) {
    // Adicionar o ponto atual no início da lista
    array_unshift($caminho, $atual);
    
    // Ir para o ponto anterior no caminho
    $atual = $anterior[$atual];
}
```

**O que significa:**
- **`array_unshift`** = "adicionar no início da lista"
- **`!== null`** = "não é vazio" (ainda tem ponto anterior)

### **ETAPA 6: Retornar resultado formatado**
```php
return [
    'caminho' => $caminho,                    // Lista com números dos pontos no caminho
    'distancia' => $distancia[$destino],     // Distância total até o destino
    'detalhes' => [
        'origem' => $nos[$origem]->rotulo ?? "Ponto $origem",      // Nome do ponto de origem
        'destino' => $nos[$destino]->rotulo ?? "Ponto $destino",   // Nome do ponto de destino
        'total_nos' => count($caminho)                          // Quantos pontos tem no caminho
    ]
];
```

**O que significa:**
- **`return`** = "devolver" (retornar o resultado)
- **`count`** = "contar quantos tem na lista"
- **`??`** = "se não existir, use isso"

**O que nosso código faz (em linguagem simples):**
- **Prepara**: busca todos os pontos e linhas do grafo
- **Cria caixinhas**: faz listas para guardar informações
- **Configura**: coloca distância 0 no ponto inicial, infinito nos outros
- **Executa**: sempre escolhe o ponto mais próximo para analisar
- **Atualiza**: quando encontra caminho melhor, atualiza as informações
- **Monta**: constrói o caminho final do destino até origem

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
**R:** "Com pesos negativos, o algoritmo pode escolher um caminho que parece melhor, mas na verdade não é. É como um GPS que não consegue voltar atrás para reconsiderar. Por isso usamos apenas pesos positivos."

**P: "Como vocês fizeram a lista de prioridade?"**
**R:** "Usamos uma lista simples do PHP. Cada ponto tem um número e uma distância. Para encontrar o menor, usamos a função `min()` do PHP. É simples mas funciona bem para grafos pequenos."

**P: "Qual a velocidade do algoritmo?"**
**R:** "O tempo cresce com o quadrado do número de pontos. Usamos lista simples, mas funciona bem para grafos pequenos. Para grafos maiores, usaríamos uma estrutura mais avançada."

**P: "Por que vocês param quando chegam ao destino?"**
**R:** "Porque já encontramos o caminho mais curto até o destino. Não precisamos analisar todos os outros pontos, economizando tempo."

**P: "Como vocês garantem que o caminho é realmente o mais curto?"**
**R:** "O algoritmo sempre escolhe o ponto com menor distância conhecida. Isso garante que quando chegamos ao destino, já temos o caminho mais curto."

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
