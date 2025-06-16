<?php
require_once('../php/conexao.php');
session_start();

// Verifica se o usuário está logado e é do tipo 1 ou 2 (admin ou gerente)
$tiposAcesso = [1,2];
$tipoUsuario = $_SESSION['usuario']['tipo'];
if (!in_array($tipoUsuario, $tiposAcesso)) {
    header('location:../login.php');
    exit();
}
switch ($tipoUsuario) {
    case 1:
        $arquivo = '../dashboard/admin.php';
        break;
    case 2:
        $arquivo = '../dashboard/gerente.php';
        break;
}

$conn = getConexao();

// Busca histórico de comissões
$sql = "SELECT h.id, u.nome as garcom_nome, h.mes_referencia, 
               h.total_vendido, h.valor_comissao, h.data_calculo
        FROM historico_comissao h
        JOIN garcom g ON h.garcom_id = g.id
        JOIN usuario u ON g.user_id = u.id
        ORDER BY h.mes_referencia DESC, h.data_calculo DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$historico = $stmt->fetchAll();

// Busca meses disponíveis para filtro
$sql_meses = "SELECT DISTINCT DATE_FORMAT(mes_referencia, '%Y-%m') as mes 
              FROM historico_comissao 
              ORDER BY mes DESC";
$stmt_meses = $conn->prepare($sql_meses);
$stmt_meses->execute();
$meses = $stmt_meses->fetchAll();

// Definir filtros padrão
$filtro_mes = isset($_GET['mes']) ? $_GET['mes'] : '';
$filtro_garcom = isset($_GET['garcom']) ? $_GET['garcom'] : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Comissões</title>
    <link rel="stylesheet" href="../css/conta.css">
</head>
<body>
    <div class="-logo-container">
        <img src="../img/logo.png" alt="Logo">
    </div>
    
    <!-- Botão de Voltar -->
    <a href="<?php echo $arquivo?>" class="btn-voltar">Voltar</a>

    <div class="filtro-container">
        <h3>Filtrar Histórico</h3>
        <form method="get" action="">
            <div class="form-group">
                <label for="mes">Mês:</label>
                <select name="mes" id="mes">
                    <option value="">Todos os meses</option>
                    <?php foreach($meses as $mes): ?>
                        <option value="<?= $mes['mes'] ?>" <?= ($filtro_mes == $mes['mes']) ? 'selected' : '' ?>>
                            <?= date('m/Y', strtotime($mes['mes'].'-01')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="garcom">Garçom:</label>
                <input type="text" name="garcom" id="garcom" placeholder="Nome do garçom" value="<?= $filtro_garcom ?>">
            </div>
            
            <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
            <a href="historico.php" class="btn btn-warning">Limpar Filtros</a>
        </form>
    </div>
    
    <div id="produtos">
        <h2>Histórico de Comissões</h2>
        <table>
            <thead>
                <tr>
                    <th>Garçom</th>
                    <th>Mês Referência</th>
                    <th>Total Vendido</th>
                    <th>Comissão (10%)</th>
                    <th>Data do Cálculo</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_vendido = 0;
                $total_comissao = 0;
                
                foreach ($historico as $item): 
                    // Aplica filtros
                    if ($filtro_mes != '' && date('Y-m', strtotime($item['mes_referencia'])) != $filtro_mes) {
                        continue;
                    }
                    
                    if ($filtro_garcom != '' && stripos($item['garcom_nome'], $filtro_garcom) === false) {
                        continue;
                    }
                    
                    $total_vendido += $item['total_vendido'];
                    $total_comissao += $item['valor_comissao'];
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['garcom_nome']) ?></td>
                        <td><?= date('m/Y', strtotime($item['mes_referencia'])) ?></td>
                        <td>R$ <?= number_format($item['total_vendido'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($item['valor_comissao'], 2, ',', '.') ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($item['data_calculo'])) ?></td>
                    </tr>
                <?php endforeach; ?>
                
                <tr class="total-row">
                    <td colspan="2"><strong>Totais</strong></td>
                    <td><strong>R$ <?= number_format($total_vendido, 2, ',', '.') ?></strong></td>
                    <td><strong>R$ <?= number_format($total_comissao, 2, ',', '.') ?></strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>