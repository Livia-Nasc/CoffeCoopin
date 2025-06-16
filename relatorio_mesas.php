<?php
session_start();

$tiposAcesso = [1,2];
$tipoUsuario = $_SESSION['usuario']['tipo'];
if (!in_array($tipoUsuario, $tiposAcesso)) {
    header('location:login.php');
    exit();
}
switch ($tipoUsuario) {
    case 1:
        $arquivo = 'dashboard/admin.php';
        break;
    case 2:
        $arquivo = 'dashboard/gerente.php';
        break;
}

require_once('php/conexao.php');
$conn = getConexao();

$data_inicio = $_POST['data_inicio'] ?? date('Y-m-01');
$data_fim = $_POST['data_fim'] ?? date('Y-m-t');
$tipo_relatorio = $_POST['tipo_relatorio'] ?? 'historico';

if ($tipo_relatorio == 'historico') {
    $sql = "SELECT c.mesa, u.nome as garcom, 
                   SUM(p.preco * pd.quantidade) as total_vendido,
                   COUNT(DISTINCT c.id) as qtd_contas
            FROM conta c
            JOIN garcom g ON c.garcom_id = g.id
            JOIN usuario u ON g.user_id = u.id
            JOIN pedido pd ON c.id = pd.conta_id
            JOIN produto p ON pd.produto_id = p.id
            WHERE c.data_abertura BETWEEN :data_inicio AND :data_fim
            GROUP BY c.mesa, u.nome
            ORDER BY c.mesa";
} else {
    $sql = "SELECT c.mesa, u.nome as garcom, 
                   c.valor_total as total_vendido,
                   DATE_FORMAT(c.data_abertura, '%d/%m/%Y') as data_abertura,
                   TIME_FORMAT(c.hora_abertura, '%H:%i') as hora_abertura
            FROM conta c
            JOIN garcom g ON c.garcom_id = g.id
            JOIN usuario u ON g.user_id = u.id
            WHERE c.status = 'aberta'
            ORDER BY c.mesa";
}

$stmt = $conn->prepare($sql);
if ($tipo_relatorio == 'historico') {
    $stmt->bindParam(':data_inicio', $data_inicio);
    $stmt->bindParam(':data_fim', $data_fim);
}
$stmt->execute();
$mesas = $stmt->fetchAll();

$total_mesas = count($mesas);
$total_vendido = array_sum(array_column($mesas, 'total_vendido'));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Mesas</title>
    <link rel="stylesheet" href="css/conta.css">
</head>
<body>
    <div class="-logo-container">
        <img src="img/logo.png" alt="Logo" class="logo-img">
    </div>
    <a href="<?php echo $arquivo?>" class="btn-voltar">Voltar</a>

    <div id="container">
        <div id="box">
            <div class="form-container">
                <h2>Relatório de Ocupação de Mesas</h2>
                
                <form method="post" action="">
                    <div class="form-group">
                        <label for="tipo_relatorio">Tipo de Relatório:</label>
                        <select name="tipo_relatorio" id="tipo_relatorio" onchange="toggleFiltros()">
                            <option value="historico" <?= $tipo_relatorio == 'historico' ? 'selected' : '' ?>>Histórico por Período</option>
                            <option value="abertas" <?= $tipo_relatorio == 'abertas' ? 'selected' : '' ?>>Mesas Atualmente Abertas</option>
                        </select>
                    </div>
                    
                    <div id="filtro-periodo" style="<?= $tipo_relatorio == 'abertas' ? 'display: none;' : '' ?>">
                        <div class="form-group">
                            <label for="data_inicio">Data Início:</label>
                            <input type="date" name="data_inicio" id="data_inicio" value="<?= $data_inicio ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="data_fim">Data Fim:</label>
                            <input type="date" name="data_fim" id="data_fim" value="<?= $data_fim ?>">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                    <button type="button" onclick="gerarPDF()" class="btn btn-primary">Gerar PDF</button>
                </form>
                
                <h3><?= $tipo_relatorio == 'historico' ? 
                    "Histórico de Mesas de " . date('d/m/Y', strtotime($data_inicio)) . " a " . date('d/m/Y', strtotime($data_fim)) : 
                    "Mesas Atualmente Abertas" ?></h3>
                
                <table>
                    <thead>
                        <tr>
                            <th>Mesa</th>
                            <th>Garçom</th>
                            <?php if ($tipo_relatorio == 'historico'): ?>
                                <th>Qtd. Contas</th>
                            <?php else: ?>
                                <th>Data Abertura</th>
                                <th>Hora Abertura</th>
                            <?php endif; ?>
                            <th>Total Vendido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mesas as $mesa){ ?>
                            <tr>
                                <td><?= $mesa['mesa'] ?></td>
                                <td><?= htmlspecialchars($mesa['garcom']) ?></td>
                                <?php if ($tipo_relatorio == 'historico'): ?>
                                    <td><?= $mesa['qtd_contas'] ?></td>
                                <?php else: ?>
                                    <td><?= $mesa['data_abertura'] ?></td>
                                    <td><?= $mesa['hora_abertura'] ?></td>
                                <?php endif; ?>
                                <td>R$ <?= number_format($mesa['total_vendido'], 2, ',', '.') ?></td>
                            </tr>
                        <?php } ?>
                        
                        <tr class="total-row">
                            <td colspan="<?= $tipo_relatorio == 'historico' ? 3 : 4 ?>"><strong>Total</strong></td>
                            <td><strong>R$ <?= number_format($total_vendido, 2, ',', '.') ?></strong></td>
                        </tr>
                    </tbody>
                </table>
                
                <div style="margin-top: 10px; font-style: italic;">
                    Total de mesas: <?= $total_mesas ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFiltros() {
            const tipoRelatorio = document.getElementById('tipo_relatorio').value;
            const filtroPeriodo = document.getElementById('filtro-periodo');
            
            if (tipoRelatorio === 'historico') {
                filtroPeriodo.style.display = 'block';
            } else {
                filtroPeriodo.style.display = 'none';
            }
        }
        
        function gerarPDF() {
            const params = new URLSearchParams(window.location.search);
            window.location.href = 'relatorio/mesas.php?' + params.toString();
        }
    </script>
</body>
</html>