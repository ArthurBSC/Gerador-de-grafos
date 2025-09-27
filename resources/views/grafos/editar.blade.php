@extends('layouts.aplicacao')

@section('titulo', 'Editar Grafo - ' . $grafo->nome)

@push('estilos')
<style>
        /* Estilos específicos da página de edição */
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--accent-color);
            border: none;
        }
        
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: var(--secondary-color);
            color: var(--text-dark);
            border: none;
        }
        
        .stats-card {
            background: rgba(255,255,255,0.8);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
        }
        
        @media (max-width: 768px) {
            .container-main {
                padding: 10px;
            }
            
            .card {
                margin: 10px 0;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
        
        /* Dark Mode para tela de edição */
        
        [data-theme="dark"] body {
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .card {
            background: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .card-header {
            background: var(--bg-tertiary);
            border-bottom-color: var(--border-color);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .card-body {
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .card-title {
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .card-text {
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .form-control {
            background: var(--bg-tertiary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .form-control:focus {
            background: var(--bg-tertiary);
            border-color: var(--cor-primaria);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .form-select {
            background: var(--bg-tertiary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .form-label {
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .form-text {
            color: var(--text-secondary);
        }
        
        [data-theme="dark"] .btn-primary {
            background: var(--cor-primaria);
            border-color: var(--cor-primaria);
        }
        
        [data-theme="dark"] .btn-primary:hover {
            background: #3a8eef;
            border-color: #3a8eef;
        }
        
        [data-theme="dark"] .btn-secondary {
            background: var(--text-secondary);
            border-color: var(--text-secondary);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .btn-secondary:hover {
            background: var(--bg-tertiary);
            border-color: var(--bg-tertiary);
        }
        
        [data-theme="dark"] .btn-outline-primary {
            color: var(--cor-primaria);
            border-color: var(--cor-primaria);
        }
        
        [data-theme="dark"] .btn-outline-primary:hover {
            background: var(--cor-primaria);
            border-color: var(--cor-primaria);
        }
        
        [data-theme="dark"] .btn-outline-secondary {
            color: var(--text-secondary);
            border-color: var(--border-color);
        }
        
        [data-theme="dark"] .btn-outline-secondary:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .btn-outline-danger {
            color: var(--cor-perigo);
            border-color: var(--cor-perigo);
        }
        
        [data-theme="dark"] .btn-outline-danger:hover {
            background: var(--cor-perigo);
            border-color: var(--cor-perigo);
        }
        
        [data-theme="dark"] .btn-outline-warning {
            color: var(--cor-aviso);
            border-color: var(--cor-aviso);
        }
        
        [data-theme="dark"] .btn-outline-warning:hover {
            background: var(--cor-aviso);
            border-color: var(--cor-aviso);
        }
        
        [data-theme="dark"] .btn-outline-info {
            color: var(--cor-info);
            border-color: var(--cor-info);
        }
        
        [data-theme="dark"] .btn-outline-info:hover {
            background: var(--cor-info);
            border-color: var(--cor-info);
        }
        
        [data-theme="dark"] .btn-outline-success {
            color: var(--cor-sucesso);
            border-color: var(--cor-sucesso);
        }
        
        [data-theme="dark"] .btn-outline-success:hover {
            background: var(--cor-sucesso);
            border-color: var(--cor-sucesso);
        }
        
        [data-theme="dark"] .btn-outline-light {
            color: var(--text-primary);
            border-color: var(--border-color);
        }
        
        [data-theme="dark"] .btn-outline-light:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .btn-outline-dark {
            color: var(--text-primary);
            border-color: var(--text-primary);
        }
        
        [data-theme="dark"] .btn-outline-dark:hover {
            background: var(--text-primary);
            color: var(--bg-primary);
        }
        
        [data-theme="dark"] .stats-card {
            background: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .list-group-item {
            background: var(--bg-secondary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .list-group-item:hover {
            background: var(--bg-tertiary);
        }
        
        [data-theme="dark"] .badge {
            color: white;
        }
        
        [data-theme="dark"] .badge.bg-primary {
            background: var(--cor-primaria) !important;
        }
        
        [data-theme="dark"] .badge.bg-secondary {
            background: var(--text-secondary) !important;
        }
        
        [data-theme="dark"] .badge.bg-success {
            background: var(--cor-sucesso) !important;
        }
        
        [data-theme="dark"] .badge.bg-danger {
            background: var(--cor-perigo) !important;
        }
        
        [data-theme="dark"] .badge.bg-warning {
            background: var(--cor-aviso) !important;
        }
        
        [data-theme="dark"] .badge.bg-info {
            background: var(--cor-info) !important;
        }
        
        [data-theme="dark"] .badge.bg-light {
            background: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .badge.bg-dark {
            background: var(--bg-primary) !important;
        }
        
        [data-theme="dark"] .alert {
            background: var(--bg-tertiary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .alert-primary {
            background: rgba(74, 158, 255, 0.1);
            border-color: var(--cor-primaria);
            color: var(--cor-primaria);
        }
        
        [data-theme="dark"] .alert-secondary {
            background: rgba(160, 160, 160, 0.1);
            border-color: var(--text-secondary);
            color: var(--text-secondary);
        }
        
        [data-theme="dark"] .alert-success {
            background: rgba(78, 222, 128, 0.1);
            border-color: var(--cor-sucesso);
            color: var(--cor-sucesso);
        }
        
        [data-theme="dark"] .alert-danger {
            background: rgba(248, 113, 113, 0.1);
            border-color: var(--cor-perigo);
            color: var(--cor-perigo);
        }
        
        [data-theme="dark"] .alert-warning {
            background: rgba(251, 191, 36, 0.1);
            border-color: var(--cor-aviso);
            color: var(--cor-aviso);
        }
        
        [data-theme="dark"] .alert-info {
            background: rgba(74, 158, 255, 0.1);
            border-color: var(--cor-info);
            color: var(--cor-info);
        }
        
        [data-theme="dark"] .alert-light {
            background: var(--bg-tertiary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .alert-dark {
            background: var(--bg-primary);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        
        [data-theme="dark"] .text-muted {
            color: var(--text-muted) !important;
        }
        
        [data-theme="dark"] .text-secondary {
            color: var(--text-secondary) !important;
        }
        
        [data-theme="dark"] .text-primary {
            color: var(--cor-primaria) !important;
        }
        
        [data-theme="dark"] .text-success {
            color: var(--cor-sucesso) !important;
        }
        
        [data-theme="dark"] .text-danger {
            color: var(--cor-perigo) !important;
        }
        
        [data-theme="dark"] .text-warning {
            color: var(--cor-aviso) !important;
        }
        
        [data-theme="dark"] .text-info {
            color: var(--cor-info) !important;
        }
        
        [data-theme="dark"] .text-light {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-dark {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-white {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-black {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-body {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-body-secondary {
            color: var(--text-secondary) !important;
        }
        
        [data-theme="dark"] .text-body-tertiary {
            color: var(--text-muted) !important;
        }
        
        [data-theme="dark"] .small, [data-theme="dark"] small {
            color: var(--text-secondary) !important;
        }
        
        [data-theme="dark"] .lead {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .fw-bold, [data-theme="dark"] .fw-bolder {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .fw-normal, [data-theme="dark"] .fw-light {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .fw-lighter {
            color: var(--text-secondary) !important;
        }
        
        [data-theme="dark"] .fst-italic {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .fst-normal {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-decoration-none {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-decoration-underline {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-decoration-line-through {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-nowrap {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-break {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-truncate {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-wrap {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-start {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-center {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-end {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-uppercase {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-lowercase {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .text-capitalize {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .lh-1 {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .lh-sm {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .lh-base {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .lh-lg {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .font-monospace {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .user-select-all {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .user-select-auto {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .user-select-none {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .pe-none {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .pe-auto {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .visible {
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] .invisible {
            color: var(--text-primary) !important;
        }
        
        /* Estilos específicos para placeholders em dark mode */
        [data-theme="dark"] .form-control::placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        [data-theme="dark"] .form-control::-webkit-input-placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        [data-theme="dark"] .form-control::-moz-placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        [data-theme="dark"] .form-control:-ms-input-placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        [data-theme="dark"] .form-control:-moz-placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        [data-theme="dark"] .form-select::placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        [data-theme="dark"] .form-select::-webkit-input-placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        [data-theme="dark"] .form-select::-moz-placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        [data-theme="dark"] .form-select:-ms-input-placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        [data-theme="dark"] .form-select:-moz-placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        /* Estilos para textarea em dark mode */
        [data-theme="dark"] textarea.form-control {
            background: var(--bg-tertiary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }
        
        [data-theme="dark"] textarea.form-control:focus {
            background: var(--bg-tertiary) !important;
            border-color: var(--cor-primaria) !important;
            color: var(--text-primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(74, 158, 255, 0.25) !important;
        }
        
        [data-theme="dark"] textarea.form-control::placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        [data-theme="dark"] textarea.form-control::-webkit-input-placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        [data-theme="dark"] textarea.form-control::-moz-placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        [data-theme="dark"] textarea.form-control:-ms-input-placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
        
        [data-theme="dark"] textarea.form-control:-moz-placeholder {
            color: var(--text-muted) !important;
            opacity: 1;
        }
    </style>
@endpush

@section('conteudo')
<div class="container-main py-4">
        <div class="row">
            <div class="col-lg-8">
                <!-- Formulário de Edição -->
                <form method="POST" action="/grafos/{{ $grafo->id }}" id="formEdicao">
                    @csrf
                    @method('PUT')
                    
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Informações Básicas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="nome" class="form-label fw-bold">Nome do Grafo *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="nome" 
                                               name="nome" 
                                               value="{{ old('nome', $grafo->nome) }}"
                                               placeholder="Nome do grafo..."
                                               required>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tipo" class="form-label fw-bold">Tipo *</label>
                                        <select class="form-select" 
                                                id="tipo" 
                                                name="tipo" 
                                                required>
                                            <option value="nao_direcionado" {{ $grafo->tipo === 'nao_direcionado' ? 'selected' : '' }}>
                                                Não-direcionado
                                            </option>
                                            <option value="direcionado" {{ $grafo->tipo === 'direcionado' ? 'selected' : '' }}>
                                                Direcionado
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="descricao" class="form-label fw-bold">Descrição</label>
                                <textarea class="form-control" 
                                          id="descricao" 
                                          name="descricao" 
                                          rows="4" 
                                          placeholder="Descrição do grafo...">{{ old('descricao', $grafo->descricao) }}</textarea>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Salvar Alterações
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="col-lg-4">
                <!-- Estatísticas do Grafo -->
                <div class="stats-card">
                    <h5 class="mb-3">
                        <i class="fas fa-chart-bar text-info me-2"></i>
                        Estatísticas
                    </h5>
                    
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="h4 text-primary mb-0">{{ $grafo->nos->count() }}</div>
                            <small class="text-muted">Nós</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-success mb-0">{{ $grafo->arestas->count() }}</div>
                            <small class="text-muted">Arestas</small>
                        </div>
                    </div>
                    
                    @if($grafo->arestas->count() > 0)
                        <hr>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="text-success fw-bold">{{ $grafo->arestas->where('peso', '>', 0)->count() }}</div>
                                <small class="text-muted">Pesos +</small>
                            </div>
                            <div class="col-6">
                                <div class="text-danger fw-bold">{{ $grafo->arestas->where('peso', '<', 0)->count() }}</div>
                                <small class="text-muted">Pesos -</small>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Nós do Grafo -->
                <div class="stats-card">
                    <h5 class="mb-3">
                        <i class="fas fa-circle text-primary me-2"></i>
                        Nós do Grafo
                    </h5>
                    
                    <div class="row">
                        @foreach($grafo->nos as $no)
                            <div class="col-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-circle me-2" style="color: {{ $no->cor }}"></i>
                                    <span class="fw-bold">{{ $no->rotulo }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Informações Técnicas -->
                <div class="stats-card">
                    <h6 class="text-muted mb-2">Informações Técnicas</h6>
                    <small class="text-muted">
                        <strong>ID:</strong> {{ $grafo->id }}<br>
                        <strong>Criado:</strong> {{ $grafo->created_at->format('d/m/Y H:i') }}<br>
                        @if($grafo->updated_at != $grafo->created_at)
                            <strong>Modificado:</strong> {{ $grafo->updated_at->diffForHumans() }}
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Validação do formulário
    document.getElementById('formEdicao').addEventListener('submit', function(e) {
        const nome = document.getElementById('nome').value.trim();
        const tipo = document.getElementById('tipo').value;
        
        if (!nome) {
            e.preventDefault();
            alert('❌ Nome do grafo é obrigatório!');
            document.getElementById('nome').focus();
            return false;
        }
        
        if (!tipo) {
            e.preventDefault();
            alert('❌ Tipo do grafo é obrigatório!');
            document.getElementById('tipo').focus();
            return false;
        }
        
        console.log('✅ Formulário válido - salvando alterações...');
    });
    
    // Mensagens de feedback
    @if(session('sucesso'))
        console.log('✅ {{ session('sucesso') }}');
    @endif
    
    @if(session('erro'))
        console.log('❌ {{ session('erro') }}');
    @endif
    
    console.log('📝 Página "Editar Grafo" carregada');
</script>
@endpush


