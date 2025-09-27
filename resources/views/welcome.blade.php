<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema Gerador de Grafos - Login</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
            transition: all 0.3s ease;
        }
        
        /* Dark Mode para Login */
        [data-theme="dark"] body {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
        }
        
        [data-theme="dark"] .login-card {
            background: #2d2d2d;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        }
        
        [data-theme="dark"] .form-label {
            color: #ffffff;
        }
        
        [data-theme="dark"] .form-control {
            background: #3a3a3a;
            border-color: #404040;
            color: #ffffff;
        }
        
        [data-theme="dark"] .form-control:focus {
            background: #3a3a3a;
            border-color: #4a9eff;
            color: #ffffff;
        }
        
        [data-theme="dark"] .btn-login {
            background: #4a9eff;
        }
        
        [data-theme="dark"] .btn-login:hover {
            background: #3a8eef;
        }
        
        [data-theme="dark"] .register-link {
            color: #a0a0a0;
        }
        
        [data-theme="dark"] .register-link:hover {
            color: #ffffff;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        
        .user-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3498db, #2ecc71);
            border-radius: 50%;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #ecf0f1;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .user-icon i {
            font-size: 40px;
            color: white;
        }
        
        .login-card {
            background: #95a5a6;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            border: none;
        }
        
        .form-label {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-control {
            border: 2px solid #bdc3c7;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            background: white;
        }
        
        .btn-login {
            background: #2980b9;
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .btn-login:hover {
            background: #1f5f8b;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .register-link {
            color: white;
            text-decoration: none;
            font-size: 14px;
            margin-top: 20px;
            display: block;
            text-align: center;
            transition: color 0.3s ease;
        }
        
        .register-link:hover {
            color: #ecf0f1;
        }
        
        .mb-3 {
            margin-bottom: 20px;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Ícone do Usuário -->
        <div class="user-icon">
            <i class="fas fa-user"></i>
        </div>
        
        <!-- Card de Login -->
        <div class="login-card">
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           value="admin@grafos.com" 
                           required 
                           autocomplete="email">
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           value="admin123" 
                           required 
                           autocomplete="current-password">
                </div>
                
                <button type="submit" class="btn btn-login">
                    Entrar
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Dark Mode System para Login
        class LoginDarkMode {
            constructor() {
                this.theme = localStorage.getItem('theme') || 'light';
                this.init();
            }
            
            init() {
                this.applyTheme();
                this.bindEvents();
            }
            
            applyTheme() {
                document.documentElement.setAttribute('data-theme', this.theme);
            }
            
            toggle() {
                this.theme = this.theme === 'light' ? 'dark' : 'light';
                this.applyTheme();
                localStorage.setItem('theme', this.theme);
            }
            
            bindEvents() {
                // Escutar mudanças de tema de outras abas
                window.addEventListener('themeChanged', (e) => {
                    this.theme = e.detail.theme;
                    this.applyTheme();
                });
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔐 Sistema de Login carregado');
            
            // Inicializar Dark Mode
            window.loginDarkMode = new LoginDarkMode();
            
            // Adicionar efeito de foco nos campos
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
            
            // Adicionar animação ao botão
            const loginBtn = document.querySelector('.btn-login');
            loginBtn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.02)';
            });
            
            loginBtn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>