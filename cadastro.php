<?php
session_start();
include("conexao.php");

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    // Prepared statement seguro
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senha);

    if ($stmt->execute()) {
        $success_message = "Cadastro realizado com sucesso! Você pode fazer login agora.";
    } else {
        $error_message = "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Cadastro - SAMoney</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
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
@media (max-width: 640px) {
    .max-w-md { 
        width: 95%; 
        padding: 1.5rem; 
    }
    h2 { font-size: 1.5rem; }
    input { padding: 0.75rem 1rem; }
    button { padding: 0.75rem 1rem; font-size: 0.95rem; }
}

@media (min-width: 641px) and (max-width: 1024px) {
    .max-w-md { 
        width: 90%; 
        padding: 2rem; 
    }
    h2 { font-size: 1.75rem; }
    input { padding: 0.85rem 1rem; }
    button { padding: 0.85rem 1rem; font-size: 1rem; }
}
</style>
</head>
<body class="min-h-screen gradient-bg flex items-center justify-center p-4">

<div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6 fade-in">
  <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Cadastro</h2>

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

  <?php if($success_message): ?>
  <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
    <div class="flex items-center">
      <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
      </svg>
      <span class="text-green-700 text-sm"><?= $success_message ?></span>
    </div>
  </div>
  <?php endif; ?>

  <form method="post" id="cadastro-form" class="space-y-6">
    <div>
      <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
      <input type="text" id="nome" name="nome" required placeholder="Seu nome" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-all duration-200" />
    </div>

    <div>
      <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
      <input type="email" id="email" name="email" required placeholder="seu@email.com" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-all duration-200" />
    </div>

    <div>
      <label for="senha" class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
      <input type="password" id="senha" name="senha" required placeholder="Sua senha" class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus transition-all duration-200" />
    </div>

    <button type="submit" class="w-full bg-orange-600 text-white py-3 px-4 rounded-lg font-medium btn-hover transition-all duration-200 flex items-center justify-center">Cadastrar</button>
  </form>

  <p class="text-center text-gray-600 mt-6">
    Já tem conta? 
    <a href="login.php" class="text-orange-600 hover:text-orange-700 font-medium transition-colors">Entrar</a>
  </p>
</div>

</body>
</html>
