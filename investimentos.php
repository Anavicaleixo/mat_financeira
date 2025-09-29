<?php
session_start();
include("conexao.php"); // Conexão com o banco MySQL

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION["usuario_id"];
$resultado = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_investimento = $_POST["tipo_investimento"] ?? 'renda_fixa';
    $valor = floatval($_POST["valor"]);
    $juros = floatval($_POST["juros"]) / 100;
    $anos = intval($_POST["anos"]);

    // Calcula o montante
    $montante = $valor * pow(1 + $juros, $anos);
    $resultado = "Tipo: " . ucfirst(str_replace("_", " ", $tipo_investimento)) . " | Montante final: R$ " . number_format($montante, 2, ",", ".");

   // Converter anos para meses se necessário
$prazo_meses = $anos * 12; // se $anos for anos

// Inserir no banco
$stmt = $conn->prepare("INSERT INTO investimentos (usuario_id, tipo, valor_investido, prazo, taxa_retorno, resultado, data_investimento) VALUES (?, ?, ?, ?, ?, ?, NOW())");

// Corrigir tipos: i = integer, s = string, d = double/float
$stmt->bind_param("isdddd", $uid, $tipo_investimento, $valor, $prazo_meses, $juros, $montante);

$stmt->execute();
$stmt->close();

}

// Exemplos inspiracionais
$exemplos = [
    ["tipo" => "Renda Fixa", "valor" => 5000, "juros" => 5, "anos" => 3],
    ["tipo" => "Fundo Imobiliário", "valor" => 10000, "juros" => 7, "anos" => 5],
    ["tipo" => "CDB", "valor" => 8000, "juros" => 6, "anos" => 4],
    ["tipo" => "LCI / LCA", "valor" => 12000, "juros" => 5.5, "anos" => 3],
    ["tipo" => "Ações", "valor" => 15000, "juros" => 10, "anos" => 5],
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Simulador de Investimentos - SAMoney</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body { box-sizing: border-box; }
.gradient-bg { background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%); }
.fade-in { animation: fadeIn 0.6s ease-in; }
@keyframes fadeIn { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }
.nav-item { transition: all 0.3s ease; }
.nav-item:hover { background-color: rgba(255, 255, 255, 0.1); }
.nav-item.active { background-color: rgba(255, 255, 255, 0.2); border-left: 4px solid #fbbf24; }
.sidebar { background-color: #f97316; min-height: 100vh; width: 256px; position: sticky; top: 0; }
.card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.card-hover:hover { transform: translateY(-8px); box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15); }
.btn-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(249,115,22,0.3); }

/* Responsividade */
@media (max-width: 640px) {
    .sidebar { width: 100%; position: relative; min-height: auto; }
    .flex { flex-direction: column; }
}
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
        <span class="text-white">Olá, <?= htmlspecialchars($_SESSION["usuario_nome"] ?? "Usuário") ?>!</span>
        <a href="logout.php" class="text-white hover:text-yellow-200 px-3 py-2 rounded-md text-sm font-medium btn-hover">Sair</a>
      </div>
    </div>
  </div>
</nav>

<div class="flex">
    <!-- Sidebar -->
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

<!-- Conteúdo principal -->
<div class="flex-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Card do Simulador -->
            <div class="bg-white rounded-xl shadow-lg p-8 flex-1 fade-in">
                <h2 class="text-2xl font-bold mb-4 flex items-center text-orange-600">Simulador de Investimentos</h2>

                <form method="post" class="space-y-4">
                    <div>
                        <label class="block font-medium mb-1">Tipo de Investimento</label>
                        <select name="tipo_investimento" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                            <option value="renda_fixa" <?= (($_POST['tipo_investimento'] ?? '')=='renda_fixa')?'selected':'' ?>>Renda Fixa</option>
                            <option value="fundo_imobiliario" <?= (($_POST['tipo_investimento'] ?? '')=='fundo_imobiliario')?'selected':'' ?>>Fundo Imobiliário</option>
                            <option value="cdb" <?= (($_POST['tipo_investimento'] ?? '')=='cdb')?'selected':'' ?>>CDB</option>
                            <option value="lci_lca" <?= (($_POST['tipo_investimento'] ?? '')=='lci_lca')?'selected':'' ?>>LCI / LCA</option>
                            <option value="acoes" <?= (($_POST['tipo_investimento'] ?? '')=='acoes')?'selected':'' ?>>Ações</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Valor Inicial (R$)</label>
                        <input type="number" name="valor" value="<?= $_POST['valor'] ?? '' ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Taxa de Juros Anual (%)</label>
                        <input type="number" step="0.01" name="juros" value="<?= $_POST['juros'] ?? '' ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Prazo (anos)</label>
                        <input type="number" name="anos" value="<?= $_POST['anos'] ?? '' ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">
                    </div>
                    <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-md font-bold hover:bg-orange-600 transition-colors">Calcular</button>
                </form>

                <?php if ($resultado): ?>
                    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-md">
                        <h3 class="font-bold text-green-800 mb-2">Resultado:</h3>
                        <p class="text-lg text-green-700"><?= $resultado ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Exemplos de Investimentos -->
            <div class="flex-1 space-y-4">
                <h2 class="text-2xl font-bold mb-4 text-orange-600">Exemplos de Investimentos</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                    <?php foreach($exemplos as $ex): 
                        $montante_ex = $ex['valor'] * pow(1 + ($ex['juros']/100), $ex['anos']);
                    ?>
                        <div class="bg-white p-4 rounded-lg shadow-md card-hover">
                            <h3 class="font-bold text-lg mb-2"><?= $ex['tipo'] ?></h3>
                            <p>Valor: R$ <?= number_format($ex['valor'],2,",",".") ?></p>
                            <p>Taxa: <?= $ex['juros'] ?>% a.a.</p>
                            <p>Prazo: <?= $ex['anos'] ?> anos</p>
                            <p class="font-semibold mt-2">Montante: R$ <?= number_format($montante_ex,2,",",".") ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
