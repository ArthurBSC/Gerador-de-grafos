# Vitor - Interface e Visualização

## O que vou mostrar
Vou demonstrar como o sistema funciona na prática e como é fácil de usar.

**Tempo**: 7 minutos

## 1. Nossa interface (1 minuto)

**O que falar:**
"Vou mostrar como criamos uma interface simples. O sistema funciona em qualquer dispositivo."

**O que torna especial:**
- Design limpo e fácil de entender
- Funciona em desktop, tablet e celular
- Tem modo escuro
- Os grafos se movem e interagem

---

## 2. Criando um grafo (2 minutos)

**Vou mostrar como é fácil:**
1. Entrar no sistema e fazer login
2. Criar um grafo novo com nossos dados de exemplo
3. Conectar os pontos com pesos diferentes
4. Salvar e ver o resultado

**Dados de exemplo:**
```
Nome: "Grafo de Demonstração"
Nós: 6 (A, B, C, D, E, F)
Arestas:
- A → B (peso: 4)
- A → C (peso: 2)
- B → D (peso: 5)
- C → D (peso: 1)
- C → E (peso: 3)
- D → F (peso: 2)
- E → F (peso: 1)
```

**Pontos importantes:**
- Formulário intuitivo com campos organizados
- Validação em tempo real
- Preview do grafo durante criação
- Suporte a 26 vértices (A até Z)

---

## 3. Visualização interativa (2 minutos)

**O que mostrar:**
1. Visualizar grafo com layout automático
2. Interagir com visualização (zoom, pan, arrastar)
3. Mostrar matriz de adjacência completa
4. Explicar representação visual dos pesos

**Funcionalidades:**
- Layout automático dos nós
- Zoom, pan, arrastar nós
- Cores diferentes para cada nó
- Pesos mostrados nas arestas
- Matriz de adjacência em tabela

## 4. Cálculo de caminho mínimo (2 minutos)

**Demonstração:**
1. Selecionar origem (ex: A)
2. Selecionar destino (ex: F)
3. Executar cálculo do caminho mínimo
4. Mostrar resultado com destaque visual
5. Explicar resultado: A→C→E→F, distância=6

**Resultado esperado:**
- Caminho mínimo: A → C → E → F
- Distância total: 6
- Tempo de cálculo: < 50ms
- Destaque visual: nós e arestas em cores diferentes

## Perguntas que podem fazer

**P: "Como funciona a visualização dos grafos?"**
**R:** "Usamos a biblioteca vis.js. Os dados do grafo são convertidos para JSON e renderizados dinamicamente com zoom, pan e interação."

**P: "Como vocês destacam o caminho mínimo?"**
**R:** "Após calcular o caminho, mudamos as cores dos nós e arestas. Os nós ficam verdes, as arestas ficam vermelhas e mais espessas."

**P: "A interface é responsiva?"**
**R:** "Sim, funciona em desktop, tablet e mobile. O menu hambúrguer aparece em telas menores."

## Dados de exemplo
```
Nome: "Grafo de Demonstração"
Nós: 6 (A, B, C, D, E, F)
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
