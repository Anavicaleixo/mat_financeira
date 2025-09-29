<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Educação Financeira - SAMoney</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* ======== Accordion ======== */
    .accordion-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.4s ease, padding 0.3s ease;
    }
    input[type="radio"]:checked ~ .accordion-content {
      max-height: 500px;
      padding-top: 1rem;
    }
    .accordion-header svg {
      transition: transform 0.3s ease;
    }
    input[type="radio"]:checked + label svg:last-child {
      transform: rotate(180deg);
    }
    .accordion-item input {
      display: none;
    }

    body { box-sizing: border-box; }
    .gradient-bg { background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%); }
    .fade-in { animation: fadeIn 0.6s ease-in; }
    @keyframes fadeIn { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }
    .btn-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(249,115,22,0.3); }
    .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover:hover { transform: translateY(-8px); box-shadow: 0 12px 20px rgba(0,0,0,0.15); }
    .sidebar { background-color: #f97316; min-height: 100vh; width: 256px; position:sticky; top:0; }
    .nav-item { transition: all 0.3s ease; }
    .nav-item:hover { background-color: rgba(255, 255, 255, 0.1); }
    .nav-item.active { background-color: rgba(255, 255, 255, 0.2); border-left: 4px solid #fbbf24; }
  </style>
</head>
<body class="bg-gray-50 font-sans">

<!-- Nav -->
<nav class="gradient-bg shadow-lg sticky top-0 z-50">
  <div class="w-full mx-auto">
    <div class="flex justify-between h-16 items-center px-0">
      <div class="flex items-start text-white font-bold text-xl">
        <img src="logo.png" alt="Logo EduFinança" class="w-[195px] h-[300px]">
      </div>
      <div class="flex items-center space-x-4">
        <span class="text-white">Olá, <?= $_SESSION['usuario_nome'] ?? 'Usuário' ?>!</span>
        <a href="logout.php" class="text-white hover:text-yellow-200 px-3 py-2 rounded-md text-sm font-medium btn-hover">Sair</a>
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
                <!-- Início -->
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
                Financiamento
            </a>

            <a href="investimentos.php" class="nav-item flex items-center px-4 py-3 text-white rounded-lg">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Investimentos
            </a>

            <a href="educacao.php" class="nav-item  flex items-center px-4 py-3 text-white rounded-lg">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
                Educação
            </a>
        </nav>
    </div>
  </div>

  <!-- Main -->
  <div class="flex-1 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Educação Financeira</h1>

    <!-- Input neutro para começar todos fechados -->
    <input type="radio" name="accordion" id="none" checked hidden>

    <div class="space-y-6">

      <!-- Card 1: Impactos dos Juros -->
      <div class="accordion-item bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 card-hover fade-in">
        <input type="radio" name="accordion" id="item1">
        <label for="item1" class="flex items-center justify-between cursor-pointer accordion-header">
          <div class="flex items-center">
            <svg class="w-8 h-8 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9 12l2 2 4-4m-7 0l2 2 4-4"/></svg>
            <h3 class="text-xl font-semibold text-gray-800">Impactos dos Juros</h3>
          </div>
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </label>
        <div class="accordion-content">
          <p class="text-gray-600 mb-4">Compreender os juros é essencial para evitar endividamento. Juros compostos podem aumentar rapidamente dívidas e também potencializar investimentos. Conheça os diferentes tipos e seus efeitos.</p>
          <a href="https://www.serasa.com.br/credito/blog/tipos-juros/" target="_blank" class="text-blue-500 hover:underline">Leia mais sobre tipos de juros →</a>
        </div>
      </div>

      <!-- Card 2: Como gerir dívidas eficientemente -->
      <div class="accordion-item bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 card-hover fade-in">
        <input type="radio" name="accordion" id="item2">
        <label for="item2" class="flex items-center justify-between cursor-pointer accordion-header">
          <div class="flex items-center">
            <svg class="w-8 h-8 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M13 7H7v6h6V7z"/></svg>
            <h3 class="text-xl font-semibold text-gray-800">Gerir Dívidas</h3>
          </div>
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </label>
        <div class="accordion-content">
          <p class="text-gray-600 mb-4">Reduza dívidas priorizando as com maiores juros, negociando parcelas e evitando gastos desnecessários. Estratégias de refinanciamento podem aliviar o orçamento.</p>
          <a href="https://www.youtube.com/watch?v=jOA2h0S-0-M" target="_blank" class="text-green-500 hover:underline">Aprenda estratégias para reduzir dívidas →</a>
        </div>
      </div>

      <!-- Card 3: Como fazer investimentos rentáveis -->
      <div class="accordion-item bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 card-hover fade-in">
        <input type="radio" name="accordion" id="item3">
        <label for="item3" class="flex items-center justify-between cursor-pointer accordion-header">
          <div class="flex items-center">
            <svg class="w-8 h-8 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M12 8V4l8 8-8 8v-4H4V8h8z"/></svg>
            <h3 class="text-xl font-semibold text-gray-800">Investimentos Rentáveis</h3>
          </div>
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </label>
        <div class="accordion-content">
          <p class="text-gray-600 mb-4">Investir permite aumentar patrimônio. Conheça renda fixa, variável, fundos e diversificação de carteira para melhores resultados.</p>
          <a href="https://www.youtube.com/watch?v=JT3PMW-vwo8" target="_blank" class="text-yellow-500 hover:underline">Assista como investir de forma rentável →</a>
        </div>
      </div>

      <!-- Card 4: Conteúdo Educativo -->
      <div class="accordion-item bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 card-hover fade-in">
        <input type="radio" name="accordion" id="item4">
        <label for="item4" class="flex items-center justify-between cursor-pointer accordion-header">
          <div class="flex items-center">
            <svg class="w-8 h-8 text-purple-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 13l4 4L19 7"/></svg>
            <h3 class="text-xl font-semibold text-gray-800">Conteúdo Educativo</h3>
          </div>
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </label>
        <div class="accordion-content">
          <p class="text-gray-600 mb-4">Artigos e vídeos explicativos sobre tipos de juros, composição e estratégias para reduzir dívidas.</p>
          <ul class="list-disc pl-5 text-gray-700">
            <li><a href="https://www.serasa.com.br/credito/blog/tipos-juros/" target="_blank" class="text-purple-500 hover:underline">Artigo: Tipos de Juros (Serasa)</a></li>
            <li><a href="https://www.youtube.com/watch?v=jOA2h0S-0-M" target="_blank" class="text-purple-500 hover:underline">Vídeo: Como reduzir dívidas</a></li>
            <li><a href="https://www.youtube.com/watch?v=JT3PMW-vwo8" target="_blank" class="text-purple-500 hover:underline">Vídeo: Investimentos rentáveis</a></li>
             <li><a href="https://www.youtube.com/watch?v=Lt_4QDjNDxc" target="_blank" class="text-purple-500 hover:underline">Vídeo: Dicas de como sair das di´vidas!</a></li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</div>

</body>
</html>
