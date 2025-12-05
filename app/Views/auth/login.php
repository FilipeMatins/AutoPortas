<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Auto Portas</title>
    
    <!-- Google Fonts - Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            position: relative;
            overflow: hidden;
        }
        
        /* Efeito de fundo animado */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(37, 99, 235, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(6, 182, 212, 0.08) 0%, transparent 40%);
            animation: pulse 15s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(-5%, 5%) rotate(1deg); }
            50% { transform: translate(5%, -5%) rotate(-1deg); }
            75% { transform: translate(-3%, -3%) rotate(0.5deg); }
        }
        
        /* Padrão de grade */
        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
        }
        
        .login-container {
            position: relative;
            z-index: 1;
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        /* Lado esquerdo - Branding */
        .login-brand {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            color: #fff;
        }
        
        .brand-content {
            max-width: 500px;
            text-align: center;
        }
        
        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-bottom: 40px;
        }
        
        .brand-logo i {
            font-size: 64px;
            color: #22d3ee;
            filter: drop-shadow(0 0 20px rgba(34, 211, 238, 0.5));
        }
        
        .brand-logo span {
            font-size: 42px;
            font-weight: 700;
            background: linear-gradient(135deg, #fff 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .brand-tagline {
            font-size: 24px;
            color: #94a3b8;
            margin-bottom: 40px;
            line-height: 1.5;
        }
        
        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 20px;
            text-align: left;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
            color: #cbd5e1;
        }
        
        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(8, 145, 178, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #22d3ee;
            font-size: 20px;
        }
        
        .feature-text h4 {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 4px;
        }
        
        .feature-text p {
            font-size: 14px;
            color: #64748b;
        }
        
        /* Lado direito - Formulário */
        .login-form-container {
            width: 480px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(10px);
            border-left: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .login-form-wrapper {
            width: 100%;
            max-width: 360px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .login-header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 8px;
        }
        
        .login-header p {
            color: #64748b;
            font-size: 15px;
        }
        
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .form-group label {
            font-size: 14px;
            font-weight: 500;
            color: #94a3b8;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 18px;
            transition: color 0.2s;
        }
        
        .input-wrapper input {
            width: 100%;
            padding: 14px 16px 14px 50px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            font-family: inherit;
            font-size: 15px;
            color: #fff;
            transition: all 0.2s;
        }
        
        .input-wrapper input::placeholder {
            color: #475569;
        }
        
        .input-wrapper input:focus {
            outline: none;
            border-color: #0891b2;
            background: rgba(8, 145, 178, 0.1);
            box-shadow: 0 0 0 4px rgba(8, 145, 178, 0.15);
        }
        
        .input-wrapper input:focus + i,
        .input-wrapper:focus-within i {
            color: #22d3ee;
        }
        
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .checkbox-wrapper input {
            display: none;
        }
        
        .checkbox-custom {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        .checkbox-wrapper input:checked + .checkbox-custom {
            background: #0891b2;
            border-color: #0891b2;
        }
        
        .checkbox-custom i {
            font-size: 12px;
            color: #fff;
            opacity: 0;
            transition: opacity 0.2s;
        }
        
        .checkbox-wrapper input:checked + .checkbox-custom i {
            opacity: 1;
        }
        
        .checkbox-wrapper span {
            font-size: 14px;
            color: #94a3b8;
        }
        
        .btn-login {
            padding: 16px 24px;
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
            border: none;
            border-radius: 12px;
            font-family: inherit;
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #22d3ee 0%, #0891b2 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(8, 145, 178, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert-error {
            padding: 14px 16px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            color: #fca5a5;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        .alert-error i {
            font-size: 20px;
            color: #f87171;
        }
        
        .login-footer {
            margin-top: 40px;
            text-align: center;
            color: #475569;
            font-size: 13px;
        }
        
        /* Responsivo */
        @media (max-width: 992px) {
            .login-brand {
                display: none;
            }
            
            .login-form-container {
                width: 100%;
                border-left: none;
            }
        }
        
        @media (max-width: 480px) {
            .login-form-container {
                padding: 24px;
            }
            
            .login-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Branding Side -->
        <div class="login-brand">
            <div class="brand-content">
                <div class="brand-logo">
                    <i class="bi bi-door-open-fill"></i>
                    <span>Auto Portas</span>
                </div>
                
                <p class="brand-tagline">
                    Sistema completo de gestão para sua empresa de portas automáticas
                </p>
                
                <div class="brand-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Gestão de Clientes</h4>
                            <p>Cadastre e gerencie seus clientes facilmente</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Orçamentos Profissionais</h4>
                            <p>Crie orçamentos detalhados em segundos</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="feature-text">
                            <h4>Dashboard Completo</h4>
                            <p>Acompanhe todas as métricas do seu negócio</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Side -->
        <div class="login-form-container">
            <div class="login-form-wrapper">
                <div class="login-header">
                    <h1>Bem-vindo de volta!</h1>
                    <p>Entre com suas credenciais para acessar o sistema</p>
                </div>
                
                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="alert-error">
                        <i class="bi bi-exclamation-circle"></i>
                        <span><?= $_SESSION['login_error'] ?></span>
                    </div>
                    <?php unset($_SESSION['login_error']); ?>
                <?php endif; ?>
                
                <form action="<?= base_url('login') ?>" method="POST" class="login-form">
                    <div class="form-group">
                        <label for="usuario">Usuário</label>
                        <div class="input-wrapper">
                            <input type="text" id="usuario" name="usuario" 
                                   placeholder="Digite seu usuário"
                                   value="<?= $_SESSION['login_usuario'] ?? '' ?>"
                                   autocomplete="username" required>
                            <i class="bi bi-person"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <div class="input-wrapper">
                            <input type="password" id="senha" name="senha" 
                                   placeholder="Digite sua senha"
                                   autocomplete="current-password" required>
                            <i class="bi bi-lock"></i>
                        </div>
                    </div>
                    
                    <div class="form-options">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" name="lembrar" value="1">
                            <span class="checkbox-custom"><i class="bi bi-check"></i></span>
                            <span>Lembrar-me</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Entrar no Sistema
                    </button>
                </form>
                
                <div class="login-footer">
                    <p>&copy; <?= date('Y') ?> Auto Portas - Todos os direitos reservados</p>
                </div>
            </div>
        </div>
    </div>
    
    <?php unset($_SESSION['login_usuario']); ?>
</body>
</html>

