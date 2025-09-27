# Pablo - Arquitetura e Estrutura

## O que vou explicar
Vou explicar como organizamos nosso código e por que escolhemos essa estrutura.

**Tempo**: 7 minutos

## 1. Como organizamos nosso código (1 minuto)

**O que falar:**
"Vou explicar como organizamos nosso código para que seja fácil de entender e manter."

**Nossos princípios:**
- Separação clara: cada parte tem sua responsabilidade
- Código organizado: lógica separada por funcionalidade
- Fácil manutenção: mudanças não quebram outras partes
- Código limpo: bem documentado e fácil de ler

---

## 2. Estrutura do projeto (2 minutos)

**Estrutura:**
```
app/
├── Http/Controllers/     # Controle (GrafoController)
├── Models/              # Dados (Grafo, NoGrafo, ArestaGrafo)
├── Services/            # Lógica de negócio (DijkstraService, GrafoService)
├── Http/Requests/       # Validação (StoreGrafoRequest, CalcularCaminhoRequest)
├── Http/Middleware/     # Middleware personalizado (ForceHttps, TrustProxies)
└── Utils/               # Utilitários (GeradorCores)
```

**Responsabilidades:**

**Controller**: Gerencia requisições HTTP
```php
class GrafoController extends Controller
{
    protected GrafoService $grafoService;
    protected DijkstraService $dijkstraService;
    
    public function __construct(GrafoService $grafoService, DijkstraService $dijkstraService)
    {
        $this->grafoService = $grafoService;
        $this->dijkstraService = $dijkstraService;
    }
}
```

**Service**: Contém lógica de negócio
```php
class DijkstraService
{
    public function calcularCaminhoMinimo(Grafo $grafo, int $origem, int $destino): array
    {
        // Implementação do algoritmo de Dijkstra
    }
}
```

**Model**: Representa dados e relacionamentos
```php
class Grafo extends Model
{
    protected $fillable = ['nome', 'descricao', 'tipo'];
    
    public function nos()
    {
        return $this->hasMany(NoGrafo::class, 'id_grafo');
    }
}
```

## 3. Boas práticas que seguimos (2 minutos)

**Princípios SOLID:**
- **Single Responsibility**: Cada classe tem uma responsabilidade
- **Open/Closed**: Extensível sem modificar código existente
- **Liskov Substitution**: Implementações podem ser trocadas
- **Interface Segregation**: Interfaces específicas
- **Dependency Inversion**: Dependências injetadas

**Qualidade do código:**
- Documentação clara
- Validação com Request classes
- Tratamento de erros
- Performance otimizada

## Perguntas que podem fazer

**P: "Por que escolheram essa arquitetura?"**
**R:** "Escolhemos MVC com Service Layer para separar responsabilidades. Controller gerencia requisições, Service contém lógica de negócio, Model representa dados."

**P: "Por que separaram a lógica em Services?"**
**R:** "Para separar lógica de negócio do controle. Facilita testes, reutilização e manutenção."

**P: "Como vocês aplicaram os princípios SOLID?"**
**R:** "Cada classe tem uma responsabilidade única, código é extensível, implementações podem ser substituídas, dependências são injetadas."

## Arquivos para mostrar
- Controller: `app/Http/Controllers/GrafoController.php`
- Service: `app/Services/DijkstraService.php`
- Model: `app/Models/Grafo.php`
- Request: `app/Http/Requests/CalcularCaminhoRequest.php`
