@extends('layouts.aplicacao')

@section('titulo', 'Sistema Gerador de Grafos')

@push('estilos')
<style>
    /* Estilos específicos da página de visualização */
</style>
@endpush

@section('conteudo')



                <!-- Seção de Detalhes (inicialmente oculta) -->
                <div id="secaoDetalhes" class="mb-4" style="display: none;">
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Tabela de Arestas -->
                            <div class="cartao-funcionalidade">
                                <h5 class="mb-3">
                                    <i class="fas fa-list text-info me-2"></i>
                                    Tabela de Arestas
                                    <span class="badge bg-info ms-2">{{ $grafo->arestas->count() }} arestas</span>
                                </h5>
                                
                                @if($grafo->arestas->count() > 0)
                                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-dark sticky-top">
                                                <tr>
                                                    <th><i class="fas fa-circle me-1"></i>Origem</th>
                                                    <th><i class="fas fa-arrow-right me-1"></i>Destino</th>
                                                    <th><i class="fas fa-weight-hanging me-1"></i>Peso</th>
                                                    <th><i class="fas fa-info-circle me-1"></i>Tipo</th>
                                                    <th><i class="fas fa-palette me-1"></i>Cor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($grafo->arestas->sortBy('peso') as $index => $aresta)
                                                    <tr>
                                                        <td>
                                                            <span class="badge fw-bold" style="background-color: {{ $aresta->noOrigem->cor }}; color: white;">
                                                                {{ $aresta->noOrigem->rotulo }}
                                                            </span>
                                                            <small class="text-muted ms-1">(ID: {{ $aresta->noOrigem->id }})</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge fw-bold" style="background-color: {{ $aresta->noDestino->cor }}; color: white;">
                                                                {{ $aresta->noDestino->rotulo }}
                                                            </span>
                                                            <small class="text-muted ms-1">(ID: {{ $aresta->noDestino->id }})</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge fs-6 {{ $aresta->peso > 0 ? 'bg-success' : ($aresta->peso < 0 ? 'bg-danger' : 'bg-secondary') }}">
                                                                {{ $aresta->peso > 0 ? '+' : '' }}{{ $aresta->peso }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if($grafo->tipo === 'direcionado')
                                                                <i class="fas fa-arrow-right text-warning" title="Direcionada"></i>
                                                                <span class="small text-muted">Direcionada</span>
                                                            @else
                                                                <i class="fas fa-arrows-alt-h text-info" title="Bidirecional"></i>
                                                                <span class="small text-muted">Bidirecional</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="rounded-circle me-2" 
                                                                     style="width: 20px; height: 20px; background-color: {{ $aresta->cor ?? ($aresta->peso > 0 ? '#2ecc71' : '#e74c3c') }};">
                                                                </div>
                                                                <small class="text-muted">{{ $aresta->peso > 0 ? 'Verde' : 'Vermelho' }}</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center p-4">
                                        <i class="fas fa-exclamation-triangle text-warning fa-2x mb-3"></i>
                                        <p class="text-muted">Nenhuma aresta encontrada neste grafo.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Matriz de Adjacência -->
                            <div class="cartao-funcionalidade">
                                <h5 class="mb-3">
                                    <i class="fas fa-table text-success me-2"></i>
                                    Matriz de Adjacência
                                    <span class="badge bg-success ms-2">{{ $grafo->nos->count() }}x{{ $grafo->nos->count() }}</span>
                                </h5>
                                
                                @php
                                    $nos = $grafo->nos->sortBy('id');
                                    $matrix = [];
                                    
                                    // Inicializar matriz com zeros
                                    foreach($nos as $origem) {
                                        foreach($nos as $destino) {
                                            $matrix[$origem->id][$destino->id] = 0;
                                        }
                                    }
                                    
                                    if ($grafo->tipo === 'direcionado') {
                                        // Para grafos direcionados: usar peso da aresta para determinar valor da matriz
                                        
                                        foreach($grafo->arestas as $aresta) {
                                            $origem = $aresta->id_no_origem;
                                            $destino = $aresta->id_no_destino;
                                            
                                            // Usar o peso da aresta para determinar o valor da matriz
                                            if ($aresta->peso > 0) {
                                                $matrix[$origem][$destino] = 1;  // Peso positivo = 1
                                            } elseif ($aresta->peso < 0) {
                                                $matrix[$origem][$destino] = -1; // Peso negativo = -1
                                            } else {
                                                $matrix[$origem][$destino] = 0;  // Peso zero = 0
                                            }
                                        }
                                        
                                        // Verificar pares de arestas bidirecionais para marcar direção reversa
                                        $paresProcessados = [];
                                        foreach($grafo->arestas as $aresta) {
                                            $origem = $aresta->id_no_origem;
                                            $destino = $aresta->id_no_destino;
                                            $chave = min($origem, $destino) . '-' . max($origem, $destino);
                                            
                                            if (!in_array($chave, $paresProcessados)) {
                                                // Verificar se existe aresta reversa
                                                $arestaReversa = $grafo->arestas->first(function($a) use ($origem, $destino) {
                                                    return $a->id_no_origem == $destino && $a->id_no_destino == $origem;
                                                });
                                                
                                                if ($arestaReversa) {
                                                    // Existe aresta bidirecional - marcar direção reversa baseada no peso
                                                    if ($arestaReversa->peso > 0) {
                                                        $matrix[$destino][$origem] = 1;  // Peso positivo = 1
                                                    } elseif ($arestaReversa->peso < 0) {
                                                        $matrix[$destino][$origem] = -1; // Peso negativo = -1
                                                    } else {
                                                        $matrix[$destino][$origem] = 0;  // Peso zero = 0
                                                    }
                                                }
                                                
                                                $paresProcessados[] = $chave;
                                            }
                                        }
                                    } else {
                                        // Para grafos não-direcionados: sempre 1 para conexões
                                        foreach($grafo->arestas as $aresta) {
                                            $origem = $aresta->id_no_origem;
                                            $destino = $aresta->id_no_destino;
                                            $matrix[$origem][$destino] = 1;
                                            $matrix[$destino][$origem] = 1;
                                        }
                                    }
                                @endphp
                                
                                <div class="table-responsive" style="max-height: 300px; overflow: auto;">
                                    <table class="table table-bordered table-sm text-center">
                                        <thead class="table-dark sticky-top">
                                            <tr>
                                                <th style="width: 40px;"></th>
                                                @foreach($nos as $no)
                                                    <th style="width: 40px; font-size: 0.8rem;" title="{{ $no->rotulo }}">
                                                        <div style="background-color: {{ $no->cor }}; width: 20px; height: 20px; border-radius: 50%; margin: 0 auto;" title="{{ $no->rotulo }}"></div>
                                                        <small>{{ $no->id }}</small>
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($nos as $origem)
                                                <tr>
                                                    <th class="table-dark" style="font-size: 0.8rem;">
                                                        <div style="background-color: {{ $origem->cor }}; width: 20px; height: 20px; border-radius: 50%; margin: 0 auto;" title="{{ $origem->rotulo }}"></div>
                                                        <small>{{ $origem->id }}</small>
                                                    </th>
                                                    @foreach($nos as $destino)
                                                        @php
                                                            $valor = $matrix[$origem->id][$destino->id];
                                                            $corClasse = '';
                                                            $icone = '';
                                                            
                                                            if ($valor == 1) {
                                                                $corClasse = 'bg-success text-white';
                                                                $icone = '1';
                                                            } elseif ($valor == -1) {
                                                                $corClasse = 'bg-danger text-white';
                                                                $icone = '-1';
                                                            } else {
                                                                $corClasse = 'bg-light text-muted';
                                                                $icone = '0';
                                                            }
                                                        @endphp
                                                        <td class="{{ $corClasse }}" style="font-weight: bold; font-size: 0.9rem;">
                                                            {{ $icone }}
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Legenda -->
                                <div class="mt-3">
                                    <small class="text-muted"><strong>Legenda:</strong></small><br>
                                    <div class="d-flex justify-content-between">
                                        <span class="badge bg-success">1</span>
                                        <small class="text-muted">{{ $grafo->tipo === 'direcionado' ? 'Aresta Direcionada (A→B)' : 'Conexão Bidirecional' }}</small>
                                    </div>
                                    @if($grafo->tipo === 'direcionado')
                                        <div class="d-flex justify-content-between">
                                            <span class="badge bg-danger">-1</span>
                                            <small class="text-muted">Direção Reversa (B→A)</small>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between">
                                        <span class="badge bg-light text-dark">0</span>
                                        <small class="text-muted">Sem Conexão</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Informações Direcionais -->
                            <div class="cartao-funcionalidade">
                                <h5 class="mb-3">
                                    <i class="fas fa-{{ $grafo->tipo === 'direcionado' ? 'long-arrow-alt-right' : 'arrows-alt-h' }} text-primary me-2"></i>
                                    Informações Direcionais
                                </h5>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                        <div>
                                            <strong>Tipo do Grafo:</strong><br>
                                            <span class="badge bg-{{ $grafo->tipo === 'direcionado' ? 'warning' : 'info' }} fs-6">
                                                {{ $grafo->tipo === 'direcionado' ? 'Direcionado' : 'Não-Direcionado' }}
                                            </span>
                                        </div>
                                        <i class="fas fa-{{ $grafo->tipo === 'direcionado' ? 'long-arrow-alt-right' : 'arrows-alt-h' }} fa-2x text-primary"></i>
                                    </div>
                                </div>

                                @if($grafo->tipo === 'direcionado')
                                    <div class="alert alert-warning">
                                        <h6><i class="fas fa-info-circle me-2"></i>Grafo Direcionado</h6>
                                        <ul class="small mb-0">
                                            <li>As arestas possuem direção específica</li>
                                            <li>Setas indicam o sentido da conexão</li>
                                            <li>A → B não implica B → A</li>
                                        </ul>
                                        <hr>
                                        <div class="d-grid">
                                            <button class="btn btn-outline-warning btn-sm" onclick="toggleDirecao()">
                                                <i class="fas fa-eye me-1"></i>Alternar Visualização
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle me-2"></i>Grafo Não-Direcionado</h6>
                                        <ul class="small mb-0">
                                            <li>As arestas são bidirecionais</li>
                                            <li>Conexões funcionam em ambos os sentidos</li>
                                            <li>A ↔ B é equivalente a B ↔ A</li>
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            <!-- Análise Estatística -->
                            <div class="cartao-funcionalidade">
                                <h5 class="mb-3">
                                    <i class="fas fa-chart-pie text-success me-2"></i>
                                    Análise Detalhada
                                </h5>
                                
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <div class="h5 text-success mb-0">{{ $grafo->arestas->where('peso', '>', 0)->count() }}</div>
                                            <small class="text-muted">Pesos Positivos</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <div class="h5 text-danger mb-0">{{ $grafo->arestas->where('peso', '<', 0)->count() }}</div>
                                            <small class="text-muted">Pesos Negativos</small>
                                        </div>
                                    </div>
                                </div>

                                @if($grafo->arestas->count() > 0)
                                    <div class="mb-3">
                                        <small class="text-muted">Distribuição de Pesos:</small>
                                        <div class="progress" style="height: 20px;">
                                            @php
                                                $positivos = $grafo->arestas->where('peso', '>', 0)->count();
                                                $negativos = $grafo->arestas->where('peso', '<', 0)->count();
                                                $total = $grafo->arestas->count();
                                                $percPositivos = $total > 0 ? ($positivos / $total) * 100 : 0;
                                                $percNegativos = $total > 0 ? ($negativos / $total) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" style="width: {{ $percPositivos }}%" title="Positivos: {{ $positivos }}">
                                                {{ number_format($percPositivos, 1) }}%
                                            </div>
                                            <div class="progress-bar bg-danger" style="width: {{ $percNegativos }}%" title="Negativos: {{ $negativos }}">
                                                {{ number_format($percNegativos, 1) }}%
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row small">
                                        <div class="col-6">
                                            <strong>Peso Mínimo:</strong><br>
                                            <span class="badge bg-danger">{{ $grafo->arestas->min('peso') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Peso Máximo:</strong><br>
                                            <span class="badge bg-success">{{ $grafo->arestas->max('peso') }}</span>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <strong>Peso Médio:</strong>
                                            <span class="badge bg-primary">{{ number_format($grafo->arestas->avg('peso'), 2) }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Visualização do Grafo -->
                    <div class="col-lg-8">
                        <div class="cartao-funcionalidade">
                            <!-- Matriz de Adjacência Completa -->
                            <div class="mb-4">
                                <div class="row align-items-center mb-3">
                                    <div class="col-xs-12 col-lg-8">
                                        <h6 class="mb-0">
                                            <i class="fas fa-calculator text-info me-2"></i>
                                            Matriz de Adjacência Completa
                                        </h6>
                                    </div>
                                    <div class="col-xs-12 col-lg-4">
                                        <div class="d-flex align-items-center justify-content-lg-end">
                                            <span class="text-muted small">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $grafo->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                        @php
                                            $nosCompletos = $grafo->nos->sortBy('id');
                                            $matrixCompleta = [];
                                            
                                            // Inicializar matriz
                                            foreach($nosCompletos as $origem) {
                                                foreach($nosCompletos as $destino) {
                                                    $matrixCompleta[$origem->id][$destino->id] = 0;
                                                }
                                            }
                                            
                                            // Usar a mesma lógica da matriz lateral para consistência
                                            if ($grafo->tipo === 'direcionado') {
                                                // Usar peso da aresta para determinar valor da matriz
                                                foreach($grafo->arestas as $aresta) {
                                                    $origem = $aresta->id_no_origem;
                                                    $destino = $aresta->id_no_destino;
                                                    
                                                    // Usar o peso da aresta para determinar o valor da matriz
                                                    if ($aresta->peso > 0) {
                                                        $matrixCompleta[$origem][$destino] = 1;  // Peso positivo = 1
                                                    } elseif ($aresta->peso < 0) {
                                                        $matrixCompleta[$origem][$destino] = -1; // Peso negativo = -1
                                                    } else {
                                                        $matrixCompleta[$origem][$destino] = 0;  // Peso zero = 0
                                                    }
                                                }
                                                
                                                // Verificar pares de arestas bidirecionais para marcar direção reversa
                                                $paresProcessadosCompleta = [];
                                                foreach($grafo->arestas as $aresta) {
                                                    $origem = $aresta->id_no_origem;
                                                    $destino = $aresta->id_no_destino;
                                                    $chaveCompleta = min($origem, $destino) . '-' . max($origem, $destino);
                                                    
                                                    if (!in_array($chaveCompleta, $paresProcessadosCompleta)) {
                                                        // Verificar se existe aresta reversa
                                                        $arestaReversaCompleta = $grafo->arestas->first(function($a) use ($origem, $destino) {
                                                            return $a->id_no_origem == $destino && $a->id_no_destino == $origem;
                                                        });
                                                        
                                                        if ($arestaReversaCompleta) {
                                                            // Existe aresta bidirecional - marcar direção reversa baseada no peso
                                                            if ($arestaReversaCompleta->peso > 0) {
                                                                $matrixCompleta[$destino][$origem] = 1;  // Peso positivo = 1
                                                            } elseif ($arestaReversaCompleta->peso < 0) {
                                                                $matrixCompleta[$destino][$origem] = -1; // Peso negativo = -1
                                                            } else {
                                                                $matrixCompleta[$destino][$origem] = 0;  // Peso zero = 0
                                                            }
                                                        }
                                                        
                                                        $paresProcessadosCompleta[] = $chaveCompleta;
                                                    }
                                                }
                                            } else {
                                                // Para grafos não-direcionados: sempre 1
                                                foreach($grafo->arestas as $aresta) {
                                                    $origem = $aresta->id_no_origem;
                                                    $destino = $aresta->id_no_destino;
                                                    $matrixCompleta[$origem][$destino] = 1;
                                                    $matrixCompleta[$destino][$origem] = 1;
                                                }
                                            }
                                        @endphp
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover text-center">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th style="width: 60px;">Origem\Destino</th>
                                                        @foreach($nosCompletos as $no)
                                                            <th style="width: 80px;">
                                                                <div class="d-flex align-items-center justify-content-center">
                                                                    <div style="background-color: {{ $no->cor }}; width: 15px; height: 15px; border-radius: 50%;" class="me-1"></div>
                                                                    <span>{{ $no->rotulo }}</span>
                                                                </div>
                                                            </th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($nosCompletos as $origem)
                                                        <tr>
                                                            <th class="table-secondary">
                                                                <div class="d-flex align-items-center justify-content-center">
                                                                    <div style="background-color: {{ $origem->cor }}; width: 15px; height: 15px; border-radius: 50%;" class="me-1"></div>
                                                                    <span>{{ $origem->rotulo }}</span>
                                                                </div>
                                                            </th>
                                                            @foreach($nosCompletos as $destino)
                                                                @php
                                                                    $valor = $matrixCompleta[$origem->id][$destino->id];
                                                                    $classe = '';
                                                                    $titulo = '';
                                                                    
                                                                    if ($valor == 1) {
                                                                        $classe = 'table-success fw-bold matrix-value-1 matrix-cell';
                                                                        $titulo = $grafo->tipo === 'direcionado' ? 'Aresta Direcionada (A→B)' : 'Conexão Bidirecional';
                                                                    } elseif ($valor == -1) {
                                                                        $classe = 'table-danger fw-bold matrix-value--1 matrix-cell';
                                                                        $titulo = 'Direção Reversa (B→A)';
                                                                    } else {
                                                                        $classe = 'table-light text-muted matrix-value-0 matrix-cell';
                                                                        $titulo = 'Sem Conexão';
                                                                    }
                                                                    
                                                                    // Adicionar classe diagonal para células A-A, B-B, etc.
                                                                    if ($origem->id == $destino->id) {
                                                                        $classe .= ' matrix-diagonal';
                                                                    }
                                                                @endphp
                                                                <td class="{{ $classe }}" title="{{ $titulo }}">
                                                                    {{ $valor }}
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Estatísticas da Matriz -->
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                @php
                                                    $totalConexoes = collect($matrixCompleta)->flatten()->filter(fn($v) => $v != 0)->count();
                                                    $conexoesPositivas = collect($matrixCompleta)->flatten()->filter(fn($v) => $v == 1)->count();
                                                    $conexoesNegativas = collect($matrixCompleta)->flatten()->filter(fn($v) => $v == -1)->count();
                                                @endphp
                                                <div class="text-center p-2 border rounded">
                                                    <div class="h5 text-primary">{{ $totalConexoes }}</div>
                                                    <small class="text-muted">Total Conexões</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center p-2 border rounded">
                                                    <div class="h5 text-success">{{ $conexoesPositivas }}</div>
                                                    <small class="text-muted">Valor 1</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center p-2 border rounded">
                                                    <div class="h5 text-danger">{{ $conexoesNegativas }}</div>
                                                    <small class="text-muted">Valor -1</small>
                                                </div>
                                            </div>
                                        </div>
                            </div>
                            
                            <div id="networkContainer"></div>
                            
                            <!-- Controles -->
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="centralizarGrafo()">
                                            <i class="fas fa-crosshairs me-1"></i>Centralizar
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-outline-info btn-sm w-100" onclick="ajustarZoom()">
                                            <i class="fas fa-search me-1"></i>Ajustar Zoom
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-outline-success btn-sm w-100" onclick="reorganizarLayout()">
                                            <i class="fas fa-random me-1"></i>Reorganizar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informações e Estatísticas -->
                    <div class="col-lg-4">
                        <!-- Estatísticas Básicas -->
                        <div class="cartao-funcionalidade">
                            <h5 class="mb-3">
                                <i class="fas fa-chart-bar text-info me-2"></i>
                                Estatísticas
                            </h5>
                            <div class="row text-center mb-3">
                                <div class="col-12">
                                    <div class="h4 text-warning mb-0">0%</div>
                                    <small class="text-muted">Densidade do Grafo</small>
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Nós -->
                        <div class="cartao-funcionalidade">
                            <h5 class="mb-3">
                                <i class="fas fa-circle text-primary me-2"></i>
                                Nós do Grafo
                            </h5>
                            <div class="list-group list-group-flush">
                                @foreach($grafo->nos as $no)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-circle me-2" style="color: {{ $no->cor }}"></i>
                                            {{ $no->rotulo }}
                                        </span>
                                        <small class="text-muted">ID: {{ $no->id }}</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Propriedades do Grafo -->
                        <div class="cartao-funcionalidade">
                            <h5 class="mb-3">
                                <i class="fas fa-cogs text-success me-2"></i>
                                Propriedades
                            </h5>
                            <ul class="list-unstyled">
                                <li>
                                    <i class="fas fa-check text-success me-2"></i>
                                    <strong>Tipo:</strong> {{ $grafo->tipo === 'direcionado' ? 'Direcionado' : 'Não-Direcionado' }}
                                </li>
                                <li>
                                    <i class="fas fa-check text-success me-2"></i>
                                    <strong>Nós:</strong> {{ $grafo->nos->count() }}
                                </li>
                                <li>
                                    <i class="fas fa-check text-success me-2"></i>
                                    <strong>Arestas:</strong> {{ $grafo->arestas->count() ?? 0 }}
                                </li>
                                <li>
                                    <i class="fas fa-check text-success me-2"></i>
                                    <strong>ID:</strong> #{{ $grafo->id }}
                                </li>
                            </ul>
                        </div>

                        <!-- Cálculo do Melhor Caminho (Algoritmo de Dijkstra) -->
                        <div class="cartao-funcionalidade">
                            <h5 class="mb-3">
                                <i class="fas fa-route text-primary me-2"></i>
                                Cálculo do Melhor Caminho (Algoritmo de Dijkstra)
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="origemCaminho" class="form-label">Nó de Origem</label>
                                    <select class="form-select" id="origemCaminho">
                                        <option value="">Selecione a origem...</option>
                                        @foreach($grafo->nos as $no)
                                            <option value="{{ $no->id }}">{{ $no->rotulo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="destinoCaminho" class="form-label">Nó de Destino</label>
                                    <select class="form-select" id="destinoCaminho">
                                        <option value="">Selecione o destino...</option>
                                        @foreach($grafo->nos as $no)
                                            <option value="{{ $no->id }}">{{ $no->rotulo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <button type="button" class="btn btn-success w-100" onclick="calcularCaminhoMinimo()">
                                        <i class="fas fa-calculator me-2"></i>Calcular Caminho
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Resultado do Caminho -->
                            <div id="resultadoCaminho" class="mt-3" style="display: none;">
                                <div class="alert alert-success">
                                    <h6><i class="fas fa-check-circle me-2"></i>Melhor Caminho Encontrado!</h6>
                                    <div id="detalhesCaminho"></div>
                                </div>
                            </div>
                            
                            <!-- Legenda do Caminho -->
                            <div class="mt-3">
                                <h6><i class="fas fa-info-circle me-2"></i>Legenda do Caminho:</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded-circle me-2" style="width: 15px; height: 15px; background-color: #2ecc71;"></div>
                                            <small><strong>Verde:</strong> Nós do caminho</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded-circle me-2" style="width: 15px; height: 15px; background-color: #e74c3c;"></div>
                                            <small><strong>Vermelho:</strong> Arestas do caminho</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded-circle me-2" style="width: 15px; height: 15px; background-color: #95a5a6; opacity: 0.6;"></div>
                                            <small><strong>Cinza:</strong> Nós não utilizados</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded me-2" style="width: 15px; height: 3px; background-color: #bdc3c7; opacity: 0.4;"></div>
                                            <small><strong>Cinza:</strong> Arestas não utilizadas</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info mt-2">
                                    <small>
                                        <strong>Como funciona:</strong> O algoritmo de Dijkstra encontra o caminho mais curto entre dois nós, 
                                        considerando os pesos das arestas.
                                    </small>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    
    <script>
        let network = null;
        let isDirected = {{ $grafo->tipo === 'direcionado' ? 'true' : 'false' }};
        let showDirections = true;
        
        // Função para alternar detalhes
        function toggleDetalhes() {
            const secao = document.getElementById('secaoDetalhes');
            const botao = event.target.closest('button');
            const icon = botao.querySelector('i');
            
            if (secao.style.display === 'none') {
                secao.style.display = 'block';
                botao.innerHTML = '<i class="fas fa-eye-slash me-2"></i>Ocultar Detalhes';
                botao.classList.remove('btn-outline-info');
                botao.classList.add('btn-info', 'text-white');
            } else {
                secao.style.display = 'none';
                botao.innerHTML = '<i class="fas fa-table me-2"></i>Ver Detalhes';
                botao.classList.remove('btn-info', 'text-white');
                botao.classList.add('btn-outline-info');
            }
        }
        
        // Função para alternar direção (apenas para grafos direcionados)
        function toggleDirecao() {
            if (!isDirected) return;
            
            showDirections = !showDirections;
            const botaoTexto = document.getElementById('botaoDirecao');
            
            if (showDirections) {
                botaoTexto.textContent = 'Direcionado';
            } else {
                botaoTexto.textContent = 'Não-direcionado';
            }
            
            // Recriar a rede com nova configuração de setas
            atualizarVisualizacao();
        }
        
        // Função para atualizar visualização
        function atualizarVisualizacao() {
            if (!network) return;
            
            // Atualizar configuração das arestas
            const novasOpcoes = {
                edges: {
                    arrows: {
                        to: { 
                            enabled: isDirected && showDirections, 
                            scaleFactor: 1.5
                        }
                    }
                }
            };
            
            network.setOptions(novasOpcoes);
            
            console.log('🔄 Visualização atualizada:', showDirections ? 'Direcionado' : 'Não-direcionado');
        }
        
        // Dados do grafo
        const nosGrafo = {!! json_encode($grafo->nos->map(function($no) {
            return [
                'id' => $no->id,
                'label' => $no->rotulo,
                'color' => $no->cor ?? '#3498db',
                'x' => $no->posicao_x ?? rand(10, 500),
                'y' => $no->posicao_y ?? rand(10, 300)
            ];
        })) !!};
        
        const arestasGrafo = {!! json_encode($grafo->arestas->map(function($aresta) {
            return [
                'from' => $aresta->id_no_origem,
                'to' => $aresta->id_no_destino,
                'label' => 'Peso: ' . $aresta->peso,
                'color' => [
                    'color' => $aresta->cor ?? ($aresta->peso > 0 ? '#2ecc71' : '#e74c3c'),
                    'highlight' => $aresta->peso > 0 ? '#27ae60' : '#c0392b'
                ],
                'width' => max(2, abs($aresta->peso) / 3 + 2),
                'font' => ['color' => '#000000', 'size' => 14, 'background' => '#ffffff']
            ];
        })) !!};
        
        // Configurar visualização
        const nodes = new vis.DataSet(nosGrafo);
        const edges = new vis.DataSet(arestasGrafo);
        
        const data = { nodes: nodes, edges: edges };
        
        const options = {
            nodes: {
                shape: 'circle',
                size: 40,
                font: {
                    size: 18,
                    color: '#ffffff',
                    face: 'Arial Black'
                },
                borderWidth: 3,
                shadow: {
                    enabled: true,
                    color: 'rgba(0,0,0,0.3)',
                    size: 8
                }
            },
            edges: {
                width: 4,
                shadow: {
                    enabled: true,
                    color: 'rgba(0,0,0,0.2)',
                    size: 3
                },
                arrows: {
                    to: { 
                        enabled: isDirected && showDirections, 
                        scaleFactor: 1.5
                    }
                },
                smooth: {
                    enabled: true,
                    type: 'dynamic',
                    roundness: 0.3
                },
                font: {
                    size: 16,
                    color: '#000000',
                    background: 'rgba(255,255,255,0.9)',
                    strokeWidth: 2,
                    strokeColor: '#ffffff',
                    align: 'middle'
                },
                labelHighlightBold: true
            },
            physics: {
                enabled: true,
                solver: 'forceAtlas2Based',
                forceAtlas2Based: {
                    gravitationalConstant: -80,
                    centralGravity: 0.005,
                    springLength: 200,
                    springConstant: 0.05
                },
                maxVelocity: 30,
                minVelocity: 0.75,
                stabilization: {
                    iterations: 200,
                    updateInterval: 25
                }
            },
            interaction: {
                dragNodes: true,
                dragView: true,
                zoomView: true,
                hover: true,
                tooltipDelay: 200
            },
            layout: {
                improvedLayout: true
            }
        };
        
        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar rede
            const container = document.getElementById('networkContainer');
            network = new vis.Network(container, data, options);
            
            // Melhorar layout inicial
            network.once('stabilizationIterationsDone', function() {
                console.log('🎯 Layout estabilizado!');
                network.fit({
                    animation: {
                        duration: 1000,
                        easingFunction: 'easeInOutQuad'
                    }
                });
            });
            
            // Adicionar eventos de interação
            network.on('selectNode', function(params) {
                const nodeId = params.nodes[0];
                const node = nosGrafo.find(n => n.id === nodeId);
                if (node) {
                    console.log('🔘 Nó selecionado:', node.label);
                    
                    // Destacar conexões do nó selecionado
                    highlightNodeConnections(nodeId);
                }
            });
            
            network.on('selectEdge', function(params) {
                const edgeId = params.edges[0];
                const edge = arestasGrafo.find(e => e.id === edgeId);
                if (edge) {
                    console.log('🔗 Aresta selecionada - Peso:', edge.label);
                }
            });
            
            // Limpar seleção ao clicar no vazio
            network.on('click', function(params) {
                if (params.nodes.length === 0 && params.edges.length === 0) {
                    clearHighlights();
                }
            });
            
            console.log('🎯 Grafo "{{ $grafo->nome }}" carregado com sucesso!');
            console.log('📊 Nós:', nosGrafo.length);
            console.log('🔗 Arestas:', arestasGrafo.length);
            console.log('🔄 Tipo:', isDirected ? 'Direcionado' : 'Não-direcionado');

        });
        
        // Função para destacar conexões de um nó
        function highlightNodeConnections(nodeId) {
            const connectedEdges = arestasGrafo.filter(edge => 
                edge.from === nodeId || edge.to === nodeId
            );
            
            // Destacar arestas conectadas (implementação opcional)
            console.log('📊 Nó tem', connectedEdges.length, 'conexões');
        }
        
        // Função para limpar destaques
        function clearHighlights() {
            // Implementação para limpar destaques (implementação opcional)
        }
        

        
        function centralizarGrafo() {
            if (network) {
                network.fit();
            }
        }
        
        function ajustarZoom() {
            if (network) {
                network.fit();
            }
        }
        
        function reorganizarLayout() {
            if (network) {
                network.setOptions({ physics: { enabled: true } });
                setTimeout(() => {
                    network.setOptions({ physics: { enabled: false } });
                }, 2000);
            }
        }
        
        
        function confirmarExclusao() {
            if (confirm('Tem certeza que deseja excluir o grafo "{{ $grafo->nome }}"?')) {
                // Criar formulário temporário para DELETE
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/grafos/{{ $grafo->id }}';
                form.style.display = 'none';
                
                // Adicionar token CSRF
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Adicionar método DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                // Adicionar ao DOM e submeter
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Função para calcular caminho mínimo usando Dijkstra
        function calcularCaminhoMinimo() {
            const origem = document.getElementById('origemCaminho').value;
            const destino = document.getElementById('destinoCaminho').value;
            
            if (!origem || !destino) {
                alert('Por favor, selecione origem e destino!');
                return;
            }
            
            if (origem === destino) {
                alert('Origem e destino devem ser diferentes!');
                return;
            }
            
            // Limpar visualização anterior
            limparCaminhoAnterior();
            
            // Executar algoritmo de Dijkstra
            const resultado = executarDijkstra(parseInt(origem), parseInt(destino));
            
            if (resultado.sucesso) {
                mostrarResultadoCaminho(resultado);
                destacarCaminhoNoGrafo(resultado.caminho, resultado.arestasCaminho);
            } else {
                alert('Não foi possível encontrar um caminho entre os nós selecionados!');
            }
        }
        
        // Implementação do algoritmo de Dijkstra
        function executarDijkstra(origem, destino) {
            const nos = nosGrafo;
            const arestas = arestasGrafo;
            
            // Inicializar estruturas
            const distancias = {};
            const anteriores = {};
            const visitados = new Set();
            const fila = new Map();
            
            // Inicializar distâncias
            nos.forEach(no => {
                distancias[no.id] = no.id === origem ? 0 : Infinity;
                anteriores[no.id] = null;
                if (no.id === origem) {
                    fila.set(no.id, 0);
                }
            });
            
            // Algoritmo principal
            while (fila.size > 0) {
                // Encontrar nó com menor distância
                let atual = null;
                let menorDistancia = Infinity;
                
                for (const [noId, distancia] of fila) {
                    if (distancia < menorDistancia) {
                        menorDistancia = distancia;
                        atual = noId;
                    }
                }
                
                if (atual === null) break;
                
                fila.delete(atual);
                visitados.add(atual);
                
                // Se chegou ao destino, parar
                if (atual === destino) break;
                
                // Verificar arestas saindo do nó atual
                arestas.forEach(aresta => {
                    if (aresta.from === atual && !visitados.has(aresta.to)) {
                        const peso = Math.abs(parseInt(aresta.label.split(': ')[1]));
                        const novaDistancia = distancias[atual] + peso;
                        
                        if (novaDistancia < distancias[aresta.to]) {
                            distancias[aresta.to] = novaDistancia;
                            anteriores[aresta.to] = atual;
                            fila.set(aresta.to, novaDistancia);
                        }
                    }
                });
            }
            
            // Reconstruir caminho
            if (distancias[destino] === Infinity) {
                return { sucesso: false };
            }
            
            const caminho = [];
            const arestasCaminho = [];
            let atual = destino;
            
            while (atual !== null) {
                caminho.unshift(atual);
                const anterior = anteriores[atual];
                
                if (anterior !== null) {
                    // Encontrar aresta entre anterior e atual
                    const aresta = arestas.find(a => a.from === anterior && a.to === atual);
                    if (aresta) {
                        arestasCaminho.unshift(aresta);
                    }
                }
                
                atual = anterior;
            }
            
            return {
                sucesso: true,
                caminho: caminho,
                arestasCaminho: arestasCaminho,
                distancia: distancias[destino],
                nosVisitados: Array.from(visitados)
            };
        }
        
        // Mostrar resultado do caminho
        function mostrarResultadoCaminho(resultado) {
            const resultadoDiv = document.getElementById('resultadoCaminho');
            const detalhesDiv = document.getElementById('detalhesCaminho');
            
            if (!resultadoDiv || !detalhesDiv) {
                console.error('Elementos de resultado não encontrados');
                return;
            }
            
            const caminhoTexto = resultado.caminho.map(id => {
                const no = nosGrafo.find(n => n.id === id);
                return no ? no.label : `Nó ${id}`;
            }).join(' → ');
            
            detalhesDiv.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Caminho:</strong> ${caminhoTexto}
                    </div>
                    <div class="col-md-6">
                        <strong>Distância Total:</strong> ${resultado.distancia}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <strong>Nós no Caminho:</strong> ${resultado.caminho.length}
                    </div>
                    <div class="col-md-6">
                        <strong>Arestas Percorridas:</strong> ${resultado.arestasCaminho.length}
                    </div>
                </div>
            `;
            
            resultadoDiv.style.display = 'block';
        }
        
        // Destacar caminho no grafo
        function destacarCaminhoNoGrafo(caminho, arestasCaminho) {
            // Atualizar cores dos nós
            const novosNos = nosGrafo.map(no => {
                if (caminho.includes(no.id)) {
                    return {
                        ...no,
                        color: '#2ecc71', // Verde para nós do caminho
                        borderWidth: 5,
                        shadow: {
                            enabled: true,
                            color: '#27ae60',
                            size: 15
                        }
                    };
                } else {
                    // Nós que não fazem parte do caminho ficam em cinza
                    return {
                        ...no,
                        color: '#95a5a6', // Cinza para nós não utilizados
                        opacity: 0.6,
                        borderWidth: 2,
                        shadow: {
                            enabled: false
                        }
                    };
                }
            });
            
            // Atualizar cores das arestas
            const novasArestas = arestasGrafo.map(aresta => {
                const isCaminho = arestasCaminho.some(a => 
                    a.from === aresta.from && a.to === aresta.to
                );
                
                if (isCaminho) {
                    return {
                        ...aresta,
                        color: {
                            color: '#e74c3c', // Vermelho para arestas do caminho
                            highlight: '#c0392b'
                        },
                        width: 8,
                        shadow: {
                            enabled: true,
                            color: '#c0392b',
                            size: 5
                        },
                        opacity: 1.0
                    };
                } else {
                    // Arestas que não fazem parte do caminho ficam em cinza
                    return {
                        ...aresta,
                        color: {
                            color: '#bdc3c7', // Cinza para arestas não utilizadas
                            highlight: '#95a5a6'
                        },
                        width: 2,
                        shadow: {
                            enabled: false
                        },
                        opacity: 0.4
                    };
                }
            });
            
            // Atualizar visualização
            nodes.update(novosNos);
            edges.update(novasArestas);
            
            console.log('🎯 Caminho destacado no grafo!');
        }
        
        // Limpar caminho anterior
        function limparCaminhoAnterior() {
            // Restaurar cores originais dos nós
            const nosOriginais = nosGrafo.map(no => ({
                ...no,
                color: no.color, // Manter cor original
                borderWidth: 3,
                opacity: 1.0,
                shadow: {
                    enabled: true,
                    color: 'rgba(0,0,0,0.3)',
                    size: 8
                }
            }));
            
            // Restaurar cores originais das arestas
            const arestasOriginais = arestasGrafo.map(aresta => ({
                ...aresta,
                color: aresta.color, // Manter cor original
                width: 4,
                opacity: 1.0,
                shadow: {
                    enabled: true,
                    color: 'rgba(0,0,0,0.2)',
                    size: 3
                }
            }));
            
            nodes.update(nosOriginais);
            edges.update(arestasOriginais);
            
            // Ocultar resultado
            const resultadoElement = document.getElementById('resultadoCaminho');
            if (resultadoElement) {
                resultadoElement.style.display = 'none';
            }
        }
    </script>
@endpush
