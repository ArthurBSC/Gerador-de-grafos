<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Sistema Gerador de Grafos')</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Global -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/grafos.css') }}">
    
    <!-- Vis.js Network -->
    <script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
    
    @stack('estilos')
</head>
<body>
    <!-- Sistema de Notificações -->
    <div class="notification-container"></div>

    <!-- Container Principal -->
    <div class="container-fluid">
        <div class="container-principal">
            
                <!-- Cabeçalho do Sistema -->
                <div class="cabecalho-sistema">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h1 class="mb-0">
                                    @if(request()->routeIs('grafos.show'))
                                        <i class="fas fa-eye me-2"></i>
                                        Visualização do Grafo
                                    @elseif(request()->routeIs('grafos.edit'))
                                        <i class="fas fa-edit me-2"></i>
                                        Edição de Grafo
                                    @elseif(request()->routeIs('grafos.create'))
                                        <i class="fas fa-plus-circle me-2"></i>
                                        Criar Grafo
                                    @elseif(isset($grafos))
                                        <i class="fas fa-project-diagram me-2"></i>
                                        Meus Grafos
                                    @else
                                        <i class="fas fa-project-diagram me-2"></i>
                                        Sistema Gerador de Grafos
                                    @endif
                                </h1>
                                <p class="mb-0 mt-2 opacity-75">
                                    @if(request()->routeIs('grafos.show'))
                                        Análise detalhada e visualização interativa do grafo
                                    @elseif(request()->routeIs('grafos.edit'))
                                        Modifique as propriedades e configurações do grafo
                                    @elseif(request()->routeIs('grafos.create'))
                                        Configure seu grafo com nós personalizados e pesos de arestas
                                    @elseif(isset($grafos))
                                        Total de {{ $grafos->count() }} {{ $grafos->count() == 1 ? 'grafo encontrado' : 'grafos encontrados' }}
                                    @else
                                        Plataforma Avançada para Criação e Análise de Grafos
                                    @endif
                                </p>
                            </div>
                        <div class="col-md-6 text-end">
                            <div class="d-flex align-items-center justify-content-end gap-3">
                                <!-- Status do Sistema -->
                                <div class="d-flex align-items-center me-3">
                                    <span class="indicador-status status-online"></span>
                                    <span class="small">Sistema Online</span>
                                </div>
                                
                                <!-- Menu Desktop (visível apenas em telas grandes) -->
                                <div class="desktop-menu d-none d-lg-flex align-items-center gap-2">
                                    <a class="btn btn-outline-light btn-sm {{ request()->routeIs('grafos.index') ? 'active' : '' }}" 
                                       href="{{ route('grafos.index') }}">
                                        <i class="fas fa-list me-1"></i>Meus Grafos
                                    </a>
                                    
                                    @if(request()->routeIs('grafos.index') && isset($grafos) && $grafos->count() > 0)
                                    <a class="btn btn-outline-light btn-sm {{ request()->routeIs('grafos.create') ? 'active' : '' }}" 
                                       href="{{ route('grafos.create') }}">
                                        <i class="fas fa-plus-circle me-1"></i>Criar Grafo
                                    </a>
                                    @elseif(!request()->routeIs('grafos.index') && !request()->routeIs('grafos.show'))
                                    <a class="btn btn-outline-light btn-sm {{ request()->routeIs('grafos.create') ? 'active' : '' }}" 
                                       href="{{ route('grafos.create') }}">
                                        <i class="fas fa-plus-circle me-1"></i>Criar Grafo
                                    </a>
                                    @endif
                                    
                                    <!-- Botões Contextuais -->
                                    @if(request()->routeIs('grafos.edit') && isset($grafo))
                                    <a class="btn btn-outline-light btn-sm" href="{{ route('grafos.show', $grafo->id) }}" title="Voltar para Visualização">
                                        <i class="fas fa-arrow-left me-1"></i>Voltar
                                    </a>
                                    @endif
                                    
                                    @if(request()->routeIs('grafos.show') && isset($grafo))
                                    <a class="btn btn-outline-light btn-sm" href="{{ route('grafos.edit', $grafo->id) }}" title="Editar Grafo">
                                        <i class="fas fa-edit me-1"></i>Editar
                                    </a>
                                    @endif
                                    
                                    <!-- Botão Atualizar -->
                                    <a class="btn btn-outline-light btn-sm" href="#" onclick="window.location.reload(); return false;" title="Atualizar Página">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                    
                                    <!-- Toggle Dark Mode -->
                                    <button class="btn btn-outline-light btn-sm" data-theme-toggle title="Alternar Modo Escuro">
                                        <i class="fas fa-moon"></i>
                                    </button>
                                    
                                    <!-- Logout -->
                                    <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm" title="Sair do Sistema">
                                        <i class="fas fa-sign-out-alt me-1"></i>Sair
                                    </a>
                                </div>
                                
                                <!-- Menu Hambúrguer (visível apenas em telas pequenas) -->
                                <div class="hamburger-menu d-lg-none">
                                    <button class="btn btn-outline-light btn-sm hamburger-toggle" id="hamburgerToggle" title="Menu">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    
                                    <!-- Menu Expandido -->
                                    <div class="hamburger-menu-content" id="hamburgerMenu">
                                        <div class="menu-items">
                                            <a class="menu-item {{ request()->routeIs('grafos.index') ? 'active' : '' }}" 
                                               href="{{ route('grafos.index') }}">
                                                <i class="fas fa-list me-2"></i>Meus Grafos
                                            </a>
                                            
                                            @if(request()->routeIs('grafos.index') && isset($grafos) && $grafos->count() > 0)
                                            <a class="menu-item {{ request()->routeIs('grafos.create') ? 'active' : '' }}" 
                                               href="{{ route('grafos.create') }}">
                                                <i class="fas fa-plus-circle me-2"></i>Criar Grafo
                                            </a>
                                            @elseif(!request()->routeIs('grafos.index') && !request()->routeIs('grafos.show'))
                                            <a class="menu-item {{ request()->routeIs('grafos.create') ? 'active' : '' }}" 
                                               href="{{ route('grafos.create') }}">
                                                <i class="fas fa-plus-circle me-2"></i>Criar Grafo
                                            </a>
                                            @endif
                                            
                                            <!-- Botões Contextuais -->
                                            @if(request()->routeIs('grafos.edit') && isset($grafo))
                                            <a class="menu-item" href="{{ route('grafos.show', $grafo->id) }}" title="Voltar para Visualização">
                                                <i class="fas fa-arrow-left me-2"></i>Voltar
                                            </a>
                                            @endif
                                            
                                            @if(request()->routeIs('grafos.show') && isset($grafo))
                                            <a class="menu-item" href="{{ route('grafos.edit', $grafo->id) }}" title="Editar Grafo">
                                                <i class="fas fa-edit me-2"></i>Editar
                                            </a>
                                            @endif
                                            
                                            <!-- Botão Atualizar -->
                                            <a class="menu-item" href="#" onclick="window.location.reload(); return false;" title="Atualizar Página">
                                                <i class="fas fa-sync-alt me-2"></i>Atualizar
                                            </a>
                                            
                                            <!-- Divisor -->
                                            <div class="menu-divider"></div>
                                            
                                            <!-- Toggle Dark Mode -->
                                            <button class="menu-item" data-theme-toggle title="Alternar Modo Escuro">
                                                <i class="fas fa-moon me-2"></i>Modo Escuro
                                            </button>
                                            
                                            <!-- Logout -->
                                            <a href="{{ route('logout') }}" class="menu-item logout" title="Sair do Sistema">
                                                <i class="fas fa-sign-out-alt me-2"></i>Sair
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Conteúdo Principal -->
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-12">
                        @yield('conteudo')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript Global -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
