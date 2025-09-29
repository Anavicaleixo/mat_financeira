<?php
session_start();
include("conexao.php");

// Redireciona para login se não estiver logado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

// Inserir despesa no banco
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["categoria"], $_POST["valor"], $_POST["data"])) {
    $categoria = $_POST["categoria"];
    $valor = $_POST["valor"];
    $data = $_POST["data"];
    $uid = $_SESSION["usuario_id"];

    $stmt = $conn->prepare("INSERT INTO despesas (usuario_id, descricao, valor, data_despesa) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $uid, $categoria, $valor, $data);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Buscar despesas do usuário
$sql = "SELECT * FROM despesas WHERE usuario_id=" . $_SESSION["usuario_id"] . " ORDER BY data_despesa DESC";
$res = $conn->query($sql);

// Buscar categorias distintas para o select do formulário
$catQuery = $conn->query("SELECT DISTINCT descricao FROM despesas WHERE usuario_id=" . $_SESSION["usuario_id"]);
$categoriasForm = [];
while ($row = $catQuery->fetch_assoc()) {
    $categoriasForm[] = $row['descricao'];
}

// Preparar dados para gráfico por categoria
$categorias = [];
$valores = [];
$catRes = $conn->query("SELECT descricao, SUM(valor) as total FROM despesas WHERE usuario_id=" . $_SESSION["usuario_id"] . " GROUP BY descricao");
while ($row = $catRes->fetch_assoc()) {
    $categorias[] = $row['descricao'];
    $valores[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SAMoney - Gerenciador de Despesas</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { box-sizing: border-box; }

.gradient-bg { background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%); }
.fade-in { animation: fadeIn 0.6s ease-in; }
@keyframes fadeIn { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }

.input-focus:focus { outline:none; border-color:#f97316; box-shadow:0 0 0 3px rgba(249,115,22,0.1); }
.btn-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(249,115,22,0.3); }

.expense-item { transition: all 0.3s ease; }
.expense-item:hover { transform: translateX(5px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

.nav-item { transition: all 0.3s ease; }
.nav-item:hover { background-color: rgba(255, 255, 255, 0.1); }
.nav-item.active { background-color: rgba(255, 255, 255, 0.2); border-left: 4px solid #fbbf24; }

.sidebar { background-color: #f97316; min-height: 100vh; width: 256px; }

.card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.card-hover:hover { transform: translateY(-8px); box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15); }

/* RESPONSIVIDADE */
@media (max-width: 1280px) {
    .flex { flex-direction: column; }
    .sidebar { width: 100%; position: relative; height: auto; }
    .flex-1 { width: 100%; }
    .grid { grid-template-columns: 1fr !important; }
    img { max-width: 100%; height: auto; }
    table { display: block; overflow-x: auto; width: 100%; }
}
</style>
</head>
<body class="bg-gray-50 font-sans">

<!-- Nav-->
<nav class="gradient-bg shadow-lg sticky top-0 z-50">
    <div class="w-full mx-auto">
        <div class="flex justify-between h-16 items-center px-0">
            <div class="flex items-start text-white font-bold text-xl">
                <img src="logo.png" alt="Logo SAMoney" class="w-[195px] h-[300px]">
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-white">Olá, <?= $_SESSION['usuario_nome'] ?? 'Usuário' ?>!</span>
                <a href="logout.php" class="text-white hover:text-yellow-200 px-3 py-2 rounded-md text-sm font-medium btn-hover">Sair</a>
            </div>
        </div>
    </div>
</nav>

<div class="flex flex-col lg:flex-row">
    <!-- Sidebar -->
    <div class="sidebar shadow-lg flex-shrink-0">
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

    <!-- Main Content -->
    <div class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Adicionar Despesa -->
            <div class="lg:col-span-1 bg-white rounded-lg shadow-lg p-6 fade-in">
                <h2 class="text-xl font-semibold mb-4">Adicionar Despesa</h2>
                <form method="post">
                    <select name="categoria" required class="w-full mb-2 px-3 py-2 border rounded-md input-focus">
                    <option value="" disabled selected>Selecione uma categoria</option>
                    <option value="Moradia">Moradia</option>
                    <option value="Alimentação">Alimentação</option>
                    <option value="Transporte">Transporte</option>
                    <option value="Lazer">Lazer</option>
                    <option value="Saúde">Saúde</option>
                    <option value="Outros">Outros</option>
                </select>

                    <input type="number" name="valor" step="0.01" placeholder="Valor" required class="w-full mb-2 px-3 py-2 border rounded-md input-focus">
                    <input type="date" name="data" required class="w-full mb-4 px-3 py-2 border rounded-md input-focus">
                    <button type="submit" class="w-full bg-orange-600 text-white py-2 px-4 rounded-md font-medium btn-hover">Adicionar</button>
                </form>
                <?php
                $total = 0;
                $count = 0;
                $res->data_seek(0);
                while ($row = $res->fetch_assoc()) {
                    $total += $row['valor'];
                    $count++;
                }
                $avg = $count ? $total / $count : 0;
                ?>
                <div class="mt-6">
                    <p>Total de Despesas: <strong class="text-red-600">R$ <?= number_format($total,2,",",".") ?></strong></p>
                    <p>Número de registros: <strong><?= $count ?></strong></p>
                    <p>Média por despesa: <strong class="text-blue-600">R$ <?= number_format($avg,2,",",".") ?></strong></p>
                </div>
            </div>

            <!-- Lista de Despesas -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6 fade-in overflow-x-auto">
    <h2 class="text-xl font-semibold mb-4">Histórico de Despesas</h2>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php
            // Primeiro SELECT
            $res->data_seek(0); // Garante que o ponteiro do resultado esteja no início
            if ($res->num_rows == 0) {
                echo '<tr><td colspan="3" class="text-center py-4 text-gray-500">Nenhuma despesa cadastrada ainda.</td></tr>';
            } else {
                while($row = $res->fetch_assoc()){
                    echo '<tr class="expense-item">';
                    echo '<td class="px-6 py-4">'.$row["descricao"].'</td>';
                    echo '<td class="px-6 py-4 text-red-600">R$ '.number_format($row["valor"],2,",",".").'</td>';
                    echo '<td class="px-6 py-4">'.$row["data_despesa"].'</td>';
                    echo '</tr>';
                }
            }

            // Segundo SELECT
            $sql2 = "SELECT descricao, valor, data_despesa FROM despesas WHERE categoria = 'Transporte' ORDER BY data_despesa DESC";
            $res2 = $conn->query($sql2);

            if ($res2 && $res2->num_rows > 0) {
                echo '<tr><td colspan="3" class="text-left font-semibold text-gray-700 pt-6">Despesas com Transporte:</td></tr>';
                while ($row2 = $res2->fetch_assoc()) {
                    echo '<tr class="expense-item">';
                    echo '<td class="px-6 py-4">'.$row2["descricao"].'</td>';
                    echo '<td class="px-6 py-4 text-blue-600">R$ '.number_format($row2["valor"],2,",",".").'</td>';
                    echo '<td class="px-6 py-4">'.$row2["data_despesa"].'</td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>


                <!-- Gráfico de Categorias -->
                <div class="bg-white rounded-lg shadow-lg p-6 fade-in">
                    <h2 class="text-xl font-semibold mb-4">Gastos por Categoria</h2>
                    <canvas id="categoryChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('categoryChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($categorias) ?>,
        datasets: [{
            label: 'Total por Categoria (R$)',
            data: <?= json_encode($valores) ?>,
            backgroundColor: 'rgba(249,115,22,0.7)',
            borderColor: 'rgba(249,115,22,1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>
