<?php
session_start();
include("conexao.php");

// Redireciona para login se não estiver logado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['usuario_id'];

// === Consultas para atualizar os cards da página inicial ===

// Total de despesas
$res = $conn->query("SELECT SUM(valor) AS total FROM despesas WHERE usuario_id = $uid");
$row = $res->fetch_assoc();
$totalDespesas = $row['total'] ?? 0;

// Total de investimentos
$res = $conn->query("SELECT SUM(valor_investido) AS total FROM investimentos WHERE usuario_id = $uid");
$row = $res->fetch_assoc();
$totalInvestimentos = $row['total'] ?? 0;

// Total de simulações de financiamento
$res = $conn->query("SELECT COUNT(*) AS total FROM financiamentos WHERE usuario_id = $uid");
$row = $res->fetch_assoc();
$totalFinanciamentos = $row['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SAMoney - Plataforma de Educação Financeira</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
/* ====== Estilos principais ====== */
body { box-sizing: border-box; font-family: sans-serif; background-color: #f9fafb; }
.gradient-bg { background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%); }
.fade-in { animation: fadeIn 0.6s ease-in; }
@keyframes fadeIn { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }
.btn-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(249,115,22,0.3); }
.card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.card-hover:hover { transform: translateY(-8px); box-shadow: 0 12px 20px rgba(0,0,0,0.15); }
.sidebar { background-color: #f97316; min-height: 100vh; width: 256px; position: sticky; top: 0; }
.nav-item { transition: all 0.3s ease; }
.nav-item:hover { background-color: rgba(255,255,255,0.1); }
.nav-item.active { background-color: rgba(255,255,255,0.2); border-left: 4px solid #fbbf24; }

/* ====== Responsividade ====== */
@media (max-width: 768px) {
  .flex { flex-direction: column; }
  .sidebar {
    width: 100% !important;
    height: auto !important;
    position: relative !important;
    display: flex !important;
    overflow-x: auto !important;
    white-space: nowrap !important;
  }
  .sidebar .p-4 {
    padding: 0.5rem !important;
    display: flex !important;
    flex-direction: row !important;
  }
  .sidebar nav {
    display: flex !important;
    flex-direction: row !important;
    gap: 0.5rem !important;
  }
  .sidebar nav a.nav-item {
    display: inline-flex !important;
    flex: 0 0 auto !important;
    min-width: 120px !important;
    border-left: none !important;
    border-bottom: 4px solid transparent !important;
    white-space: nowrap !important;
    padding-left: 1rem !important;
    padding-right: 1rem !important;
  }
  .sidebar nav a.nav-item.active {
    border-left: none !important;
    border-bottom-color: #fbbf24 !important;
  }
  .sidebar nav a.nav-item:hover {
    border-bottom-color: rgba(255,255,255,0.3) !important;
  }
  
  /* Esconde nome do usuário no mobile */
  nav span.text-white {
    display: none !important;
  }
  
  /* Logo maior em mobile */
  nav div img {
    width: 200px !important;
    max-height: 60px !important;
    object-fit: contain !important;
  }

  /* Centralizar logo melhor */
  nav > div > div.flex.justify-between {
    justify-content: center !important;
  }
  nav > div > div.flex.items-center.space-x-4 {
    justify-content: flex-end !important;
    width: auto !important;
  }

  /* Espaçamento extra entre cards */
  .card-hover {
    margin-bottom: 1rem !important;
  }
}

@media (max-width: 480px) {
  .sidebar nav a.nav-item {
    min-width: 100px !important;
    font-size: 0.875rem !important;
    padding-left: 0.75rem !important;
    padding-right: 0.75rem !important;
  }
  nav div img {
    width: 180px !important;
    max-height: 55px !important;
  }
  nav span.text-white {
    display: none !important;
  }
}
</style>
</head>
<body>

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


<div class="flex">
  <!-- Sidebar -->
  <div class="sidebar shadow-lg">
    <div class="p-4">
      <nav class="space-y-2">
        <a href="index.php" class="nav-item flex items-center px-4 py-3 text-white rounded-lg">
          <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
          </svg>
          Início
        </a>
        <a href="despesas.php" class="nav-item flex items-center px-4 py-3 text-white rounded-lg">
          <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
          </svg>
          Despesas
        </a>
        <a href="financiamento.php" class="nav-item flex items-center px-4 py-3 text-white rounded-lg">
          <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm3 5a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd"/>
          </svg>
          Financiamentos
        </a>
        <a href="investimentos.php" class="nav-item flex items-center px-4 py-3 text-white rounded-lg">
          <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          Investimentos
        </a>
        <a href="educacao.php" class="nav-item flex items-center px-4 py-3 text-white rounded-lg">
          <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
          </svg>
          Educação
        </a>
      </nav>
    </div>
  </div>

  <!-- Main Content -->
  <div class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-12 fade-in">
      <h1 class="text-4xl font-bold text-gray-800 mb-4">Bem-vindo à SAMoney</h1>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto">
        Sua plataforma completa para educação financeira. Gerencie despesas, simule financiamentos, 
        calcule investimentos e aprenda sobre finanças pessoais.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <!-- Cards -->
      <div class="card-hover bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 fade-in">
        <div class="flex items-center mb-4">
          <svg class="w-8 h-8 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
          </svg>
          <h3 class="text-xl font-semibold text-gray-800">Gerenciador de Despesas</h3>
        </div>
        <p class="text-gray-600 mb-4">Controle seus gastos mensais e categorize suas despesas</p>
        <div class="text-2xl font-bold text-blue-600">R$ <?= number_format($totalDespesas, 2, ',', '.') ?></div>
        <div class="text-sm text-gray-500">Despesas do mês</div>
      </div>

      <div class="card-hover bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 fade-in">
        <div class="flex items-center mb-4">
          <svg class="w-8 h-8 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          <h3 class="text-xl font-semibold text-gray-800">Investimentos</h3>
        </div>
        <p class="text-gray-600 mb-4">Simule rendimentos e planeje seus investimentos</p>
        <div class="text-2xl font-bold text-green-600">R$ <?= number_format($totalInvestimentos, 2, ',', '.') ?></div>
        <div class="text-sm text-gray-500">Total investido</div>
      </div>

      <div class="card-hover bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 fade-in">
        <div class="flex items-center mb-4">
          <svg class="w-8 h-8 text-purple-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm3 5a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd"/>
          </svg>
          <h3 class="text-xl font-semibold text-gray-800">Financiamentos</h3>
        </div>
        <p class="text-gray-600 mb-4">Simule financiamentos e compare condições</p>
        <div class="text-2xl font-bold text-purple-600"><?= $totalFinanciamentos ?></div>
        <div class="text-sm text-gray-500">Simulações realizadas</div>
      </div>

      <div class="card-hover bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500 fade-in">
        <div class="flex items-center mb-4">
          <svg class="w-8 h-8 text-orange-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
          </svg>
          <h3 class="text-xl font-semibold text-gray-800">Educação Financeira</h3>
        </div>
        <p class="text-gray-600 mb-4">Aprenda conceitos importantes sobre finanças</p>
        <div class="text-2xl font-bold text-orange-600">100%</div>
        <div class="text-sm text-gray-500">Conteúdo gratuito</div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
