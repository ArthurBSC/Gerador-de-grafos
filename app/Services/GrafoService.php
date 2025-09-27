<?php

namespace App\Services;

use App\Models\Grafo;
use App\Models\NoGrafo;
use App\Models\ArestaGrafo;
use App\Utils\GeradorCores;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Serviço responsável pela geração e criação de grafos
 * 
 * Responsabilidades:
 * - Criar grafos com nós e arestas
 * - Gerar grafos aleatórios
 * - Validar estruturas de grafos
 * - Aplicar algoritmos de geração
 */
class GrafoService
{
    protected GeradorCores $geradorCores;

    public function __construct(GeradorCores $geradorCores)
    {
        $this->geradorCores = $geradorCores;
    }

    /**
     * Cria um grafo completo com nós e arestas
     */
    public function criarGrafo(array $dados): Grafo
    {
        $grafo = $this->criarGrafoPrincipal($dados);
        $this->criarNos($grafo, $dados);
        $this->criarArestas($grafo, $dados);
        
        return $grafo;
    }

    /**
     * Atualiza um grafo existente
     */
    public function atualizarGrafo(Grafo $grafo, array $dados): void
    {
        $grafo->update([
            'nome' => $dados['nome'],
            'descricao' => $dados['descricao'] ?? null,
            'tipo' => $dados['tipo']
        ]);
    }

    /**
     * Exclui um grafo e todos os seus relacionamentos
     */
    public function excluirGrafo(Grafo $grafo): void
    {
        // O cascade delete no banco já remove nós e arestas
        $grafo->delete();
    }

    /**
     * Cria o grafo principal
     */
    private function criarGrafoPrincipal(array $dados): Grafo
    {
        return Grafo::create([
            'nome' => $dados['nome'],
            'descricao' => $dados['descricao'] ?? null,
            'tipo' => $dados['tipo'],
            'quantidade_nos' => $dados['quantidade_nos'],
            'configuracoes_visuais' => [
                'cores_padrao' => ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6'],
                'animacoes' => true
            ]
        ]);
    }

    /**
     * Cria os nós do grafo
     */
    private function criarNos(Grafo $grafo, array $dados): void
    {
        $paleta = ['#3498db', '#e74c3c', '#2ecc71', '#f39c12', '#9b59b6'];
        
        for ($i = 0; $i < $dados['quantidade_nos']; $i++) {
            NoGrafo::create([
                'id_grafo' => $grafo->id,
                'rotulo' => chr(65 + $i), // A, B, C, ...
                'cor' => $paleta[$i % count($paleta)],
                'posicao_x' => rand(50, 500),
                'posicao_y' => rand(50, 300),
                'tamanho' => 20
            ]);
        }
    }

    /**
     * Cria as arestas do grafo
     */
    private function criarArestas(Grafo $grafo, array $dados): void
    {
        $nos = $grafo->nos;
        
        switch ($dados['modo_pesos']) {
            case 'automatico':
                $this->criarArestasAutomaticas($grafo, $nos);
                break;
            case 'especifico':
                // Passar os dados de conexões diretamente
                $conexoes = [
                    'conexoes_origem' => $dados['conexoes_origem'] ?? [],
                    'conexoes_destino' => $dados['conexoes_destino'] ?? [],
                    'conexoes_peso' => $dados['conexoes_peso'] ?? []
                ];
                $this->criarArestasEspecificas($grafo, $nos, $conexoes);
                break;
        }
    }

    /**
     * Cria arestas automaticamente (grafo completo)
     */
    private function criarArestasAutomaticas(Grafo $grafo, $nos): void
    {
        foreach ($nos as $origem) {
            foreach ($nos as $destino) {
                if ($origem->id !== $destino->id) {
                    $peso = $grafo->tipo === 'direcionado' ? 
                        rand(-10, 10) : abs(rand(-10, 10));
                    
                    ArestaGrafo::create([
                        'id_grafo' => $grafo->id,
                        'id_no_origem' => $origem->id,
                        'id_no_destino' => $destino->id,
                        'peso' => $peso,
                        'cor' => $peso > 0 ? '#2ecc71' : '#e74c3c',
                        'largura' => 3
                    ]);
                }
            }
        }
    }

    /**
     * Cria arestas específicas
     */
    private function criarArestasEspecificas(Grafo $grafo, $nos, array $conexoes): void
    {
        
        // Converter array de conexões para o formato correto
        $conexoesProcessadas = [];
        
        // Se as conexões vêm do formulário como arrays separados
        if (isset($conexoes['conexoes_origem']) && isset($conexoes['conexoes_destino']) && isset($conexoes['conexoes_peso'])) {
            $origens = $conexoes['conexoes_origem'];
            $destinos = $conexoes['conexoes_destino'];
            $pesos = $conexoes['conexoes_peso'];
            
            
            for ($i = 0; $i < count($origens); $i++) {
                if (isset($origens[$i]) && isset($destinos[$i]) && isset($pesos[$i])) {
                    $conexoesProcessadas[] = [
                        'origem' => (int)$origens[$i],
                        'destino' => (int)$destinos[$i],
                        'peso' => (int)$pesos[$i]
                    ];
                }
            }
        } else {
            $conexoesProcessadas = $conexoes;
        }
        
        
        $arestasCriadas = 0;
        foreach ($conexoesProcessadas as $conexao) {
            // Buscar pelos índices dos nós (os IDs vêm do formulário como índices 0, 1, 2, etc.)
            $origem = $nos->values()->get($conexao['origem']);
            $destino = $nos->values()->get($conexao['destino']);
            
            
            if ($origem && $destino) {
                    ArestaGrafo::create([
                        'id_grafo' => $grafo->id,
                        'id_no_origem' => $origem->id,
                        'id_no_destino' => $destino->id,
                        'peso' => $conexao['peso'],
                        'cor' => $conexao['peso'] > 0 ? '#2ecc71' : '#e74c3c',
                        'largura' => 2
                    ]);
                $arestasCriadas++;
            }
        }
        
    }

    /**
     * Valida se a estrutura de um grafo é consistente
     */
    public function validarEstrutura(Grafo $grafo): array
    {
        $erros = [];
        $avisos = [];

        // Verificar se há nós
        if ($grafo->nos()->count() === 0) {
            $erros[] = 'Grafo não possui nós';
        }

        // Verificar consistência da quantidade de nós
        $quantidadeReal = $grafo->nos()->count();
        if ($grafo->quantidade_nos !== $quantidadeReal) {
            $avisos[] = "Quantidade de nós registrada ({$grafo->quantidade_nos}) difere da real ({$quantidadeReal})";
        }

        // Verificar arestas inválidas
        foreach ($grafo->arestas as $aresta) {
            if (!$aresta->ehValida()) {
                $erros[] = "Aresta inválida encontrada (ID: {$aresta->id})";
            }
        }

        // Verificar nós isolados
        $nosIsolados = $grafo->nos()->get()->filter(fn($no) => $no->ehIsolado());
        if ($nosIsolados->count() > 0) {
            $avisos[] = "Encontrados {$nosIsolados->count()} nós isolados";
        }

        return [
            'valido' => empty($erros),
            'erros' => $erros,
            'avisos' => $avisos
        ];
    }
}
