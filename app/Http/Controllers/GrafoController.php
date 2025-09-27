<?php

namespace App\Http\Controllers;

use App\Models\Grafo;
use App\Models\NoGrafo;
use App\Models\ArestaGrafo;
use App\Http\Requests\StoreGrafoRequest;
use App\Http\Requests\UpdateGrafoRequest;
use App\Http\Requests\CalcularCaminhoRequest;
use App\Services\GrafoService;
use App\Services\DijkstraService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para gerenciamento de grafos
 * 
 * Seguindo as melhores práticas do Laravel:
 * - Separação de responsabilidades
 * - Injeção de dependências
 * - Request classes para validação
 * - Service classes para lógica de negócio
 */
class GrafoController extends Controller
{
    protected GrafoService $grafoService;
    protected DijkstraService $dijkstraService;

    public function __construct(GrafoService $grafoService, DijkstraService $dijkstraService)
    {
        $this->grafoService = $grafoService;
        $this->dijkstraService = $dijkstraService;
    }

    /**
     * Lista todos os grafos com paginação otimizada
     */
    public function index(): View
    {
        $this->verificarAutenticacao();
        
        try {
            $paginacao = 15;
            
            $grafos = Grafo::with(['nos', 'arestas'])
                ->orderBy('created_at', 'desc')
                ->paginate($paginacao);
            
            Log::info('LISTAGEM: Total de grafos carregados: ' . $grafos->count());
            
            return view('grafos.indice', compact('grafos'));
        } catch (\Exception $e) {
            Log::error('Erro ao listar grafos: ' . $e->getMessage());
            return view('grafos.indice', ['grafos' => collect()]);
        }
    }

    /**
     * Exibe formulário de criação de grafo
     */
    public function create(): View
    {
        $this->verificarAutenticacao();
        return view('grafos.criar');
    }

    /**
     * Cria um novo grafo com validação otimizada
     */
    public function store(StoreGrafoRequest $request): RedirectResponse
    {
        $this->verificarAutenticacao();
        
        try {
            $dados = $request->validated();
            
            
            // Usar o serviço para criar o grafo
            $grafo = $this->grafoService->criarGrafo($dados);
            
            Log::info('Grafo criado com sucesso: ' . $grafo->nome);
            
            return redirect()
                ->route('grafos.show', $grafo->id)
                ->with('sucesso', 'Grafo criado com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Erro ao criar grafo: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('erro', 'Erro ao criar grafo: ' . $e->getMessage());
        }
    }

    /**
     * Visualiza um grafo específico
     */
    public function show(int $id): View|RedirectResponse
    {
        $this->verificarAutenticacao();
        
        try {
            $grafo = Grafo::with(['nos', 'arestas.noOrigem', 'arestas.noDestino'])
                ->findOrFail($id);
            
            return view('grafos.visualizar', compact('grafo'));
        } catch (\Exception $e) {
            Log::error('Erro ao visualizar grafo: ' . $e->getMessage());
            return redirect()
                ->route('grafos.index')
                ->with('erro', 'Grafo não encontrado');
        }
    }

    /**
     * Exibe formulário de edição de grafo
     */
    public function edit(int $id): View|RedirectResponse
    {
        $this->verificarAutenticacao();
        
        try {
            $grafo = Grafo::with(['nos', 'arestas'])->findOrFail($id);
            return view('grafos.editar', compact('grafo'));
        } catch (\Exception $e) {
            return redirect()->route('grafos.index')
                ->with('erro', 'Grafo não encontrado.');
        }
    }

    /**
     * Atualiza grafo existente
     */
    public function update(UpdateGrafoRequest $request, int $id): RedirectResponse
    {
        $this->verificarAutenticacao();
        
        try {
            $grafo = Grafo::findOrFail($id);
            $dados = $request->validated();
            
            // Atualizar dados básicos
            $grafo->update($dados);
            
            return redirect()->route('grafos.show', $grafo->id)
                ->with('sucesso', 'Grafo atualizado com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('erro', 'Erro ao atualizar grafo: ' . $e->getMessage());
        }
    }

    /**
     * Exclui um grafo e todos os seus relacionamentos
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $grafo = Grafo::findOrFail($id);
            $nomeGrafo = $grafo->nome;
            
            // Excluir grafo (cascade delete remove nós e arestas)
            $grafo->delete();
            
            Log::info('Grafo excluído: ' . $nomeGrafo);
            
            return response()->json([
                'sucesso' => true,
                'mensagem' => 'Grafo "' . $nomeGrafo . '" excluído com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao excluir grafo: ' . $e->getMessage());
            
            return response()->json([
                'sucesso' => false,
                'erro' => 'Erro ao excluir grafo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcula caminho mínimo usando algoritmo de Dijkstra
     */
    public function calcularCaminhoMinimo(CalcularCaminhoRequest $request, int $id): JsonResponse
    {
        try {
            $grafo = Grafo::with(['nos', 'arestas'])->findOrFail($id);
            $dados = $request->validated();
            $origem = $dados['origem'];
            $destino = $dados['destino'];
            
            // Usar o serviço Dijkstra
            $caminho = $this->dijkstraService->calcularCaminhoMinimo($grafo, $origem, $destino);
            
            return response()->json([
                'sucesso' => true,
                'caminho' => $caminho['caminho'],
                'distancia' => $caminho['distancia'],
                'detalhes' => $caminho['detalhes']
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro no cálculo de caminho mínimo: ' . $e->getMessage());
            
            return response()->json([
                'sucesso' => false,
                'erro' => 'Erro no cálculo: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Verifica se o usuário está autenticado
     */
    private function verificarAutenticacao(): void
    {
        if (!session('user_logged_in')) {
            abort(redirect('/')->with('erro', 'Você precisa fazer login para acessar esta página.'));
        }
    }
}
