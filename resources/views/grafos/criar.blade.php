@extends('layouts.aplicacao')

@section('titulo', 'Criar Grafo')
@section('subtitulo', 'Configure seu grafo com nós personalizados e pesos de arestas')

@push('estilos')
<style>
    /* Estilos específicos da página de criação */
    
    /* Dark Mode para tela de criação */
    [data-theme="dark"] .cartao-funcionalidade {
        background: var(--bg-secondary);
        border-color: var(--border-color);
        color: var(--text-primary);
    }
    
    [data-theme="dark"] .container-nos {
        background: var(--bg-tertiary);
        border-color: var(--border-color);
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
    
    [data-theme="dark"] .btn-outline-primary {
        color: var(--cor-primaria);
        border-color: var(--cor-primaria);
    }
    
    [data-theme="dark"] .btn-outline-primary:hover {
        background: var(--cor-primaria);
        color: white;
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
        color: white;
    }
    
    [data-theme="dark"] .btn-outline-warning {
        color: var(--cor-aviso);
        border-color: var(--cor-aviso);
    }
    
    [data-theme="dark"] .btn-outline-warning:hover {
        background: var(--cor-aviso);
        color: white;
    }
    
    [data-theme="dark"] .btn-outline-info {
        color: var(--cor-info);
        border-color: var(--cor-info);
    }
    
    [data-theme="dark"] .btn-outline-info:hover {
        background: var(--cor-info);
        color: white;
    }
    
    [data-theme="dark"] .btn-outline-success {
        color: var(--cor-sucesso);
        border-color: var(--cor-sucesso);
    }
    
    [data-theme="dark"] .btn-outline-success:hover {
        background: var(--cor-sucesso);
        color: white;
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
        background: rgba(34, 211, 238, 0.1);
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
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-xl-10 mx-auto">
                <!-- Formulário Principal -->
                <form id="formularioGrafo" method="POST" action="/grafos" onsubmit="return validarEnvio()">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Informações Básicas -->
                            <div class="cartao-funcionalidade">
                                <h5 class="mb-3">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    Informações Básicas
                                </h5>
                                
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="nome" class="form-label">Nome do Grafo *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="nome" 
                                               name="nome" 
                                               placeholder="Ex: Rede Social, Mapa de Cidades..."
                                               required>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="tipo" class="form-label">Tipo *</label>
                                        <select class="form-select" 
                                                id="tipo" 
                                                name="tipo" 
                                                required>
                                            <option value="">Selecione...</option>
                                            <option value="nao_direcionado">Não-direcionado</option>
                                            <option value="direcionado">Direcionado</option>
                                        </select>
                                    </div>
                                </div>
                        
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="descricao" class="form-label">Descrição</label>
                                            <textarea class="form-control" 
                                                      id="descricao" 
                                                      name="descricao" 
                                                      rows="3" 
                                                      placeholder="Descreva o propósito e características do seu grafo..."></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="modo_pesos" class="form-label">
                                                <i class="fas fa-weight-hanging text-warning"></i> Pesos das Arestas
                                            </label>
                                            <select class="form-select" id="modo_pesos" name="modo_pesos" required onchange="togglePesosMode()">
                                                <option value="automatico">Automático (Grafo Completo)</option>
                                                <option value="especifico">Específico (Conexões Escolhidas)</option>
                                            </select>
                                            <div class="form-text">
                                                <small>
                                                    <strong>Automático:</strong> grafo completo com pesos aleatórios<br>
                                                    <strong>Específico:</strong> escolha exatamente quais nós se conectam
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Container para Conexões Específicas -->
                            <div id="containerConexoesEspecificas" class="cartao-funcionalidade mb-4" style="display: none;">
                                <h5 class="mb-3">
                                    <i class="fas fa-project-diagram text-primary me-2"></i>
                                    Definir Conexões Específicas
                                </h5>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Modo Específico:</strong> Selecione exatamente quais nós devem se conectar. Você pode criar grafos com conexões parciais.
                                </div>
                                <div id="listaConexoes" class="row">
                                    <!-- Os campos de conexão serão gerados dinamicamente -->
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="adicionarConexao()">
                                        <i class="fas fa-plus me-1"></i>Adicionar Conexão
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Configuração dos Nós -->
                            <div class="cartao-funcionalidade mb-4">
                                <h5 class="mb-3">
                                    <i class="fas fa-circle text-info me-2"></i>
                                    Configuração dos Nós
                                </h5>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="quantidade_nos" class="form-label">Quantidade de Nós *</label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="quantidade_nos" 
                                               name="quantidade_nos" 
                                               min="2" 
                                               max="26" 
                                               value="5" 
                                               required
                                               onchange="atualizarNos()">
                                        <div class="form-text">Entre 2 e 26 nós (A-Z)</div>
                                    </div>
                                    
                                    <div class="col-md-6 d-flex align-items-end">
                                        <button type="button" 
                                                class="btn btn-outline-primary btn-custom me-2" 
                                                onclick="gerarNos()">
                                            <i class="fas fa-magic me-2"></i>Gerar Nós
                                        </button>
                                        <button type="button" 
                                                class="btn btn-outline-info btn-custom" 
                                                onclick="limparNos()">
                                            <i class="fas fa-eraser me-2"></i>Limpar
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="container-nos" id="containerNos">
                                    <!-- Os nós serão gerados dinamicamente aqui -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Painel Lateral -->
                        <div class="col-lg-4">
                            <!-- Preview do Grafo -->
                            <div class="cartao-funcionalidade sticky-top">
                                <h5 class="mb-3">
                                    <i class="fas fa-eye text-success me-2"></i>
                                    Preview
                                </h5>
                                
                                <div id="previewInfo" class="mb-3">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="h4 text-primary mb-0" id="totalNos">5</div>
                                            <small class="text-muted">Nós</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="h4 text-success mb-0" id="totalArestas">10</div>
                                            <small class="text-muted">Arestas</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert alert-light" id="infoArestas">
                                    <small>
                                        <strong>ℹ️ Informação:</strong><br>
                                        <span id="textoInfoArestas">
                                            O sistema cria um grafo completo onde todos os nós se conectam entre si.
                                            Para <strong>n nós</strong>, teremos <strong>n(n-1)/2 arestas</strong>.
                                        </span>
                                    </small>
                                </div>
                                
                                <!-- Botão de Criar -->
                                <div class="d-grid">
                                    <button type="submit" 
                                            class="btn btn-success btn-lg" 
                                            id="btnCriar">
                                        <i class="fas fa-rocket me-2"></i>
                                        Criar Grafo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let quantidadeNos = 5;
        
        function togglePesosMode() {
            const modo = document.getElementById('modo_pesos').value;
            const containerEspecifico = document.getElementById('containerConexoesEspecificas');
            
            // Ocultar container específico
            containerEspecifico.style.display = 'none';
            
            if (modo === 'especifico') {
                containerEspecifico.style.display = 'block';
                gerarConexoesEspecificas();
                atualizarPreview();
            } else {
                atualizarPreview();
            }
        }
        

        function gerarNos() {
            quantidadeNos = parseInt(document.getElementById('quantidade_nos').value);
            atualizarNos();
        }
        
        function limparNos() {
            document.getElementById('containerNos').innerHTML = '';
        }
        
        function gerarConexoesEspecificas() {
            const quantidade = parseInt(document.getElementById('quantidade_nos').value);
            const container = document.getElementById('listaConexoes');
            
            // Limpar campos existentes
            container.innerHTML = '';
            
            // Adicionar uma conexão inicial
            adicionarConexao();
        }
        
        function adicionarConexao() {
            const quantidade = parseInt(document.getElementById('quantidade_nos').value);
            const container = document.getElementById('listaConexoes');
            
            // Gerar opções de nós
            let opcoesNos = '';
            for (let i = 0; i < quantidade; i++) {
                const letra = String.fromCharCode(65 + i);
                opcoesNos += `<option value="${i}">${letra}</option>`;
            }
            
            const conexaoId = Date.now(); // ID único para esta conexão
            
            const colDiv = document.createElement('div');
            colDiv.className = 'col-md-12 mb-3';
            colDiv.id = `conexao-${conexaoId}`;
            
            colDiv.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <label class="form-label">Origem</label>
                                <select class="form-select" name="conexoes_origem[]" required>
                                    ${opcoesNos}
            </select>
                            </div>
                            <div class="col-md-1 text-center">
                                <i class="fas fa-arrow-right text-primary mt-4"></i>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Destino</label>
                                <select class="form-select" name="conexoes_destino[]" required>
                                    ${opcoesNos}
            </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Peso</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="conexoes_peso[]" 
                                       value="1"
                                       min="-50" 
                                       max="50"
                                       required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" 
                                        class="btn btn-outline-danger w-100" 
                                        onclick="removerConexao(${conexaoId})">
                <i class="fas fa-trash"></i>
            </button>
                            </div>
                        </div>
                    </div>
        </div>
    `;
    
            container.appendChild(colDiv);
        }
        
        function removerConexao(conexaoId) {
            const elemento = document.getElementById(`conexao-${conexaoId}`);
            if (elemento) {
                elemento.remove();
    atualizarPreview();
            }
        }
        
        function atualizarPreview() {
            const quantidade = parseInt(document.getElementById('quantidade_nos').value);
            const modo = document.getElementById('modo_pesos').value;
            
            let totalArestas = 0;
            let textoInfo = '';
            
            if (modo === 'especifico') {
                // Contar conexões específicas
                const conexoes = document.querySelectorAll('#listaConexoes .card');
                totalArestas = conexoes.length;
                textoInfo = `Modo específico: você define exatamente quais nós se conectam. Atualmente: <strong>${totalArestas} conexões</strong>.`;
            } else {
                // Grafo completo
                totalArestas = (quantidade * (quantidade - 1)) / 2;
                textoInfo = `O sistema cria um grafo completo onde todos os nós se conectam entre si. Para <strong>${quantidade} nós</strong>, teremos <strong>${totalArestas} arestas</strong>.`;
            }
            
            // Atualizar contadores
            document.getElementById('totalNos').textContent = quantidade;
            document.getElementById('totalArestas').textContent = totalArestas;
            document.getElementById('textoInfoArestas').innerHTML = textoInfo;
        }
        
        function atualizarNos() {
            const quantidade = parseInt(document.getElementById('quantidade_nos').value);
            const container = document.getElementById('containerNos');
            
            // Validar quantidade
            if (quantidade < 2) {
                alert('❌ Mínimo de 2 nós necessário!');
                document.getElementById('quantidade_nos').value = 2;
                return;
            }
            
            if (quantidade > 26) {
                alert('❌ Máximo de 26 nós (A-Z) permitido!');
                document.getElementById('quantidade_nos').value = 26;
                return;
            }
            
            // Limpar campos existentes
            container.innerHTML = '';
            
            console.log('🔄 Atualizando nós para quantidade:', quantidade);
            
            // Gerar novos campos
            for (let i = 0; i < quantidade; i++) {
                const letra = String.fromCharCode(65 + i); // A, B, C, etc.
                
                const colDiv = document.createElement('div');
                colDiv.className = 'col-md-6 col-lg-4 mb-3';
                
                colDiv.innerHTML = `
        <div class="input-group">
            <span class="input-group-text">
                            <i class="fas fa-circle" style="color: hsl(${(i * 360 / quantidade)}, 70%, 50%);"></i>
            </span>
                        <input type="text" 
                               class="form-control" 
                               name="rotulos_nos[]" 
                               value="${letra}" 
                               placeholder="Rótulo do nó ${i + 1}"
                               required>
        </div>
    `;
    
                container.appendChild(colDiv);
            }
            
            quantidadeNos = quantidade;
            
            // Atualizar preview
            document.getElementById('totalNos').textContent = quantidade;
            document.getElementById('totalArestas').textContent = (quantidade * (quantidade - 1)) / 2;
            
            console.log('✅ Nós atualizados! Total:', quantidadeNos);
            atualizarPreview();
}


        function validarEnvio() {
            console.log('🚀 Enviando formulário...');
            
            // Verificar token CSRF
            const csrfToken = document.querySelector('input[name="_token"]');
            console.log('🔒 Token CSRF:', csrfToken ? 'Presente' : 'AUSENTE');
    
    const nome = document.getElementById('nome').value.trim();
    const tipo = document.getElementById('tipo').value;
            const nos = document.querySelectorAll('input[name="rotulos_nos[]"]');
            
            if (!nome) {
                alert('❌ Nome do grafo é obrigatório!');
                return false;
            }
            
            if (!tipo) {
                alert('❌ Tipo do grafo é obrigatório!');
                return false;
            }
            
            if (nos.length < 2) {
                alert('❌ Mínimo de 2 nós necessário!');
                return false;
            }
            
            console.log('✅ Validação passou - submetendo formulário...');
            return true;
        }

        // Configuração inicial
        document.addEventListener('DOMContentLoaded', function() {
            atualizarNos();
            console.log('📝 Página "Criar Grafo" carregada');
        });
    </script>
@endsection