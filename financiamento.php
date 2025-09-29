<?php
session_start();
include("conexao.php");

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$resultado = "";
if (isset($_POST['simular'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $tipo = $_POST['tipo'] ?? 'imobiliario';
    $valor = floatval($_POST['valor']);
    $juros = floatval($_POST['juros']) / 100;
    $parcelas = intval($_POST['parcelas']);

    $prestacao = ($valor * $juros) / (1 - pow(1 + $juros, -$parcelas));
    $resultado = "Tipo: " . ucfirst($tipo) . " | Valor da prestação: R$ " . number_format($prestacao, 2, ",", ".");

   
    $juros_percent = $juros * 100; 
    $prestacao_val = $prestacao;  

    $stmt = $conn->prepare("INSERT INTO financiamentos (usuario_id, tipo, valor, juros, parcelas, prestacao) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isddid", $usuario_id, $tipo, $valor, $juros_percent, $parcelas, $prestacao_val);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SAMoney - Plataforma de Educação Financeira</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
    body { box-sizing: border-box; }
    .gradient-bg { background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%); }
    .fade-in { animation: fadeIn 0.6s ease-in; }
    @keyframes fadeIn { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }
    .sidebar { background-color: #f97316; min-height: 100vh; width: 256px; position: sticky; top: 0; }
    .nav-item { transition: all 0.3s ease; }
    .nav-item:hover { background-color: rgba(255, 255, 255, 0.1); }
    .nav-item.active { background-color: rgba(255, 255, 255, 0.2); border-left: 4px solid #fbbf24; }
    .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-8px); box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15); }
</style>
</head>
<body class="bg-gray-50 font-sans">

<!-- Navbar -->
<nav class="gradient-bg shadow-lg sticky top-0 z-50">
  <div class="w-full mx-auto">
    <div class="flex justify-between h-16 items-center px-0">
      <div class="flex items-start text-white font-bold text-xl">
        <img src="logo.png" alt="Logo SAMoney" class="w-[195px] h-[300px]">
      </div>
      <div class="flex items-center space-x-4">
        <span class="text-white">Olá, <?= $_SESSION['usuario_nome'] ?? 'Usuário' ?>!</span>
        <a href="logout.php" class="text-white hover:text-yellow-200 px-3 py-2 rounded-md text-sm font-medium">Sair</a>
      </div>
    </div>
  </div>
</nav>

<!-- Conteúdo principal -->
<div class="flex">
    <div class="sidebar shadow-lg">
    <div class="p-4">
        <nav class="space-y-2">
            <a href="index.php" class="nav-item flex items-center px-4 py-3 text-white rounded-lg">
                <!-- Início -->
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                Início
            </a>
            <a href="despesas.php" class="nav-item flex items-center px-4 py-3 text-white rounded-lg">
                <!-- Despesas -->
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
                Despesas
            </a>
            <a href="financiamento.php" class="nav-item flex items-center px-4 py-3 text-white rounded-lg">
                <!-- Financiamento -->
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm3 5a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
                Financiamento
            </a>
            <a href="investimentos.php" class="nav-item flex items-center px-4 py-3 text-white rounded-lg">
                <!-- Investimentos -->
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Investimentos
            </a>
            <a href="educacao.php" class="nav-item flex items-center px-4 py-3 text-white rounded-lg">
                <!-- Educação -->
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
                Educação
            </a>
        </nav>
    </div>
</div>


<!-- Main Content -->
<div class="flex-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Card do Simulador -->
            <div class="bg-white rounded-xl shadow-lg p-8 flex-1 fade-in">
                <h2 class="text-2xl font-bold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M18 9l-4 4-5-5-5 5" />
                    </svg>
                    Simulador de Financiamento
                </h2>

                <form method="post" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Tipo de financiamento</label>
                        <select name="tipo" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                            <option value="imobiliario" <?= (($_POST['tipo'] ?? '')=='imobiliario') ? 'selected' : '' ?>>Imobiliário</option>
                            <option value="veicular" <?= (($_POST['tipo'] ?? '')=='veicular') ? 'selected' : '' ?>>Veicular</option>
                            <option value="pessoal" <?= (($_POST['tipo'] ?? '')=='pessoal') ? 'selected' : '' ?>>Pessoal</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Valor financiado (R$)</label>
                        <input type="number" name="valor" value="<?= $_POST['valor'] ?? '' ?>" required
                               class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Taxa de juros (%)</label>
                        <input type="number" step="0.01" name="juros" value="<?= $_POST['juros'] ?? '' ?>" required
                               class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Número de parcelas</label>
                        <input type="number" name="parcelas" value="<?= $_POST['parcelas'] ?? '' ?>" required
                               class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                    </div>

                    <button type="submit" name="simular"
                        class="w-full bg-orange-600 text-white py-3 rounded-md font-medium hover:bg-orange-700 transition-colors flex items-center justify-center">
                        Simular
                    </button>
                </form>

                <?php if (!empty($resultado)): ?>
                    <div class="mt-6 p-4 bg-orange-100 border-l-4 border-orange-400 rounded-md text-lg font-semibold text-orange-800">
                        <?= $resultado ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Exemplos de Financiamento -->
            <div class="flex-1 space-y-4">
                <h3 class="text-xl font-bold mb-4 text-orange-600 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.5 0-3 .5-4 1.5S6 12 6 14s.5 3 1.5 4S10 20 12 20s3-.5 4-1.5S18 16 18 14s-.5-3-1.5-4S13.5 8 12 8z" />
                    </svg>
                    Exemplos de Financiamento
                </h3>
                <div class="grid grid-cols-1 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow card-hover">
                        <p class="font-semibold">Financiamento Imobiliário</p>
                        <p class="text-gray-600 text-sm">Valor: R$ 300.000 | Parcelas: 360 | Juros: 0,7% a.m.</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow card-hover">
                        <p class="font-semibold">Financiamento Veicular</p>
                        <p class="text-gray-600 text-sm">Valor: R$ 50.000 | Parcelas: 60 | Juros: 1,2% a.m.</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow card-hover">
                        <p class="font-semibold">Empréstimo Pessoal</p>
                        <p class="text-gray-600 text-sm">Valor: R$ 10.000 | Parcelas: 24 | Juros: 2,0% a.m.</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow card-hover">
                        <p class="font-semibold">Financiamento para Reforma</p>
                        <p class="text-gray-600 text-sm">Valor: R$ 20.000 | Parcelas: 36 | Juros: 1,0% a.m.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
