<?php
session_start();
include("conexao.php");

$error_message = "";

// Processa login quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($senha, $user["senha"])) {
            $_SESSION["usuario_id"] = $user["id"];
            $_SESSION["usuario_nome"] = $user["nome"];
            // Redireciona para a página interna
            header("Location: index.php");
            exit;
        } else {
            $error_message = "Senha incorreta.";
        }
    } else {
        $error_message = "Email não cadastrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login - SAMoney</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
/* === Seus estilos customizados === */
body { box-sizing: border-box; }

.gradient-bg { 
    background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%); 
}

.fade-in { 
    animation: fadeIn 0.6s ease-in; 
}

@keyframes fadeIn { 
    from { opacity:0; transform: translateY(20px); } 
    to { opacity:1; transform: translateY(0); } 
}

.input-focus:focus { 
    outline:none; 
    border-color:#f97316; 
    box-shadow:0 0 0 3px rgba(249,115,22,0.1); 
}

.btn-hover:hover { 
    transform:translateY(-2px); 
    box-shadow:0 10px 20px rgba(249,115,22,0.3); 
}

.error-message { 
    animation: shake 0.5s ease-in-out; 
}

@keyframes shake { 
    0%,100%{transform:translateX(0);} 
    25%{transform:translateX(-5px);} 
    75%{transform:translateX(5px);} 
}

/* === RESPONSIVIDADE === */

/* Mobile adjustments */
@media (max-width: 640px) {
    .max-w-md { 
        width: 95%; 
    }
    h1 { 
        font-size: 1.5rem; 
    }
    h2 { 
        font-size: 1.25rem; 
    }
    input { 
        padding: 0.75rem 1rem; 
    }
    .btn-hover { 
        padding: 0.75rem 1rem; 
        font-size: 0.95rem;
    }
}

/* Tablets adjustments */
@media (min-width: 641px) and (max-width: 1024px) {
    .max-w-md { 
        width: 90%; 
    }
    h1 { 
        font-size: 1.75rem; 
    }
    h2 { 
        font-size: 1.5rem; 
    }
}
</style>

</head>
<body class="min-h-screen gradient-bg flex items-center justify-center p-4">

<div class="w-full max-w-md">
  <div class="text-center mb-6 fade-in mt-4 pb-2">
    <h1 class="text-white text-2xl font-semibold mb-2">Entre na sua conta da SAMoney</h1>
    <p class="text-white/80 text-lg">O próximo passo da sua educação financeira começa aqui.</p>
  </div>

  <!-- Formulário de login -->
  <div class="bg-white rounded-2xl shadow-2xl p-6 fade-in">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Faça o seu login</h2>

    <?php if($error_message): ?>
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 error-message">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
            <span class="text-red-700 text-sm"><?= $error_message ?></span>
        </div>
    </div>
    <?php endif; ?>

    <form method="post" id="login-form">
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-all duration-200" placeholder="seu@email.com" />
        </div>

        <div class="mb-6">
            <label for="senha" class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
            <div class="relative">
                <input type="password" id="senha" name="senha" required class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-all duration-200 pr-12" placeholder="Digite sua senha" />
                <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center">
                <input type="checkbox" id="remember" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500" />
                <span class="ml-2 text-sm text-gray-600">Lembrar de mim</span>
            </label>
            <a href="#" onclick="alert('Recuperação de senha será implementada em breve!');" class="text-sm text-orange-600 hover:text-orange-700 transition-colors">Esqueceu a senha?</a>
        </div>

        <button type="submit" class="w-full bg-orange-600 text-white py-3 px-4 rounded-lg font-medium btn-hover transition-all duration-200 flex items-center justify-center">Entrar</button>
    </form>

    <p class="text-center text-gray-600 mt-4">
        Não tem conta? 
        <a href="cadastro.php" class="text-orange-600 hover:text-orange-700 font-medium transition-colors">Cadastre-se aqui</a>
    </p>
  </div> 
</div>

<script>
function togglePassword(){
    const passwordInput = document.getElementById('senha');
    const eyeIcon = document.getElementById('eye-icon');
    if(passwordInput.type==='password'){
        passwordInput.type='text';
        eyeIcon.innerHTML=`<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>`;
    } else {
        passwordInput.type='password';
        eyeIcon.innerHTML=`<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
}
</script>
</body>
</html>