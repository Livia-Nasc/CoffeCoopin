<?php
require_once('../php/conexao.php');
session_start();

// Verifica se o usuário está logado e é do tipo 1 ou 3 (admin ou garçom)
$tiposAcesso = [1,3];
$tipoUsuario = $_SESSION['usuario']['tipo'];
if (!in_array($tipoUsuario, $tiposAcesso)) {
    header('location:../login.php');
    exit();
}

switch ($tipoUsuario) {
    case 1:
        $arquivo = '../dashboard/admin.php';
        break;
    case 3:
        $arquivo = '../dashboard/garcom.php';
        break;
}

$conn = getConexao();
$user_id = $_SESSION['usuario']['id'];

// Busca id do garçom
if($tipoUsuario == 3){
    $sql_garcom = "SELECT id FROM garcom WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql_garcom);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $dadosUsuario = $stmt->fetch();
    $garcom_id = $dadosUsuario['id'];
}

// Definir filtro padrão
$filtro_status = isset($_GET['status']) ? $_GET['status'] : 'todas';

// Se não houver contas na sessão, buscar do banco
if (!isset($_SESSION['contas']) || empty($_SESSION['contas'])) {
    $sql = "SELECT * FROM conta WHERE garcom_id = :garcom_id ORDER BY data_abertura DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':garcom_id', $garcom_id);
    $stmt->execute();
    $_SESSION['contas'] = $stmt->fetchAll();
}

// Busca produtos se não estiverem na sessão
if (!isset($_SESSION['produtos']) || empty($_SESSION['produtos'])) {
    $sql_produtos = "SELECT id, nome, preco, porcao, qtd_estoque FROM produto ORDER BY nome";
    $stmt_produtos = $conn->prepare($sql_produtos);
    $stmt_produtos->execute();
    $_SESSION['produtos'] = $stmt_produtos->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de Contas</title>
    <link rel="stylesheet" href="../css/conta.css">
</head>
<body>
    <div class="-logo-container">
        <img src="../img/logo.png" alt="Logo">
    </div>
    
    <!-- Botão de Voltar -->
    <a href="<?php echo $arquivo?>" class="btn-voltar">Voltar</a>

    <div class="filtro-container">
        <h3>Filtrar Contas</h3>
        <div>
            <a href="?status=todas" class="filtro-btn <?php echo $filtro_status == 'todas' ? 'active' : ''; ?>">Todas as Contas</a>
            <a href="?status=aberta" class="filtro-btn <?php echo $filtro_status == 'aberta' ? 'active' : ''; ?>">Contas Abertas</a>
            <a href="?status=fechada" class="filtro-btn <?php echo $filtro_status == 'fechada' ? 'active' : ''; ?>">Contas Fechadas</a>
            <a href="?status=cancelada" class="filtro-btn <?php echo $filtro_status == 'cancelada' ? 'active' : ''; ?>">Contas Canceladas</a>
        </div>
    </div>
    
    <div id="produtos">
        <h2>Lista de Contas</h2>
        <form method="post">
            <button type="submit" name="visualizar" class="btn btn-primary">Atualizar Lista de Contas</button>
            <a href="../abrir_conta.php" class="btn-voltar">Criar conta</a>
        </form>
        
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div style="padding: 10px; background: #f8d7da; color: #721c24; border-radius: 4px; margin: 10px 0;">
                <?php 
                    echo $_SESSION['mensagem'];
                    unset($_SESSION['mensagem']);
                ?>
            </div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Mesa</th>
                    <th>Data Abertura</th>
                    <th>Status</th>
                    <th>Valor Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_SESSION['contas'])): ?>
                    <?php foreach ($_SESSION['contas'] as $conta): ?>
                        <?php 
                            // Aplicar filtro de status
                            if ($filtro_status != 'todas' && $conta['status'] != $filtro_status) {
                                continue;
                            }
                            
                            // Buscar produtos da conta
                            $sql_produtos = "SELECT pd.id as pedido_id, p.nome, p.preco, pd.quantidade 
                                             FROM pedido pd
                                             JOIN produto p ON pd.produto_id = p.id
                                             WHERE pd.conta_id = :conta_id";
                            $stmt_produtos = $conn->prepare($sql_produtos);
                            $stmt_produtos->bindParam(':conta_id', $conta['id']);
                            $stmt_produtos->execute();
                            $produtos_conta = $stmt_produtos->fetchAll();
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($conta['mesa']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($conta['data_abertura'])) ?></td>
                            <td><?= ucfirst($conta['status']) ?></td>
                            <td>R$ <?= number_format($conta['valor_total'], 2, ',', '.') ?></td>
                            <td>
                                <?php if ($conta['status'] == 'aberta'): ?>
                                    <form method="post" action="../php/conta.php" style="display: inline;">
                                        <input type="hidden" name="conta_id" value="<?= $conta['id'] ?>">
                                        <button type="submit" name="fechar_conta" class="btn btn-warning" 
                                            onclick="return confirm('Tem certeza que deseja fechar esta conta?')">
                                            Fechar
                                        </button>
                                    </form>
                                    <form method="post" action="../php/conta.php" style="display: inline;">
                                        <input type="hidden" name="conta_id" value="<?= $conta['id'] ?>">
                                        <button type="submit" name="cancelar_conta" class="btn btn-primary" onclick="return confirm('Tem certeza que deseja cancelar essa conta?')">Cancelar Conta</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="padding: 0;">
                                <div class="produtos-conta">
                                    <strong>Produtos:</strong>
                                    <?php if (!empty($produtos_conta)): ?>
                                        <?php 
                                            $total_conta = 0;
                                            foreach ($produtos_conta as $produto): 
                                                $subtotal = $produto['preco'] * $produto['quantidade'];
                                                $total_conta += $subtotal;
                                        ?>
                                            <div class="produto-item">
                                                <span>
                                                    <?= htmlspecialchars($produto['nome']) ?>
                                                    (<?= $produto['quantidade'] ?> x R$ <?= number_format($produto['preco'], 2, ',', '.') ?>)
                                                    
                                                    <?php if ($conta['status'] == 'aberta'): ?>
                                                        <form method="post" action="../php/conta.php" style="display: inline;">
                                                            <input type="hidden" name="pedido_id" value="<?= $produto['pedido_id'] ?>">
                                                            <button type="submit" name="excluir_pedido" class="btn-excluir"
                                                                onclick="return confirm('Tem certeza que deseja excluir este item?')">
                                                                Excluir
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </span>
                                                <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="produto-item total-conta">
                                            <span>Total da Conta:</span>
                                            <span>R$ <?= number_format($total_conta, 2, ',', '.') ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div>Nenhum produto associado</div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhuma conta encontrada</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>