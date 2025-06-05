<?php
require_once('../php/conexao.php');
session_start();

// Verifica se o usuário está logado e é do tipo 3 (garçom)
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

// Busca produtos
$sql = "SELECT id, nome, preco, porcao, qtd_estoque FROM produto";
$stmt = $conn->prepare($sql);
$stmt->execute();
$_SESSION['produtos'] = $stmt->fetchAll();

// Busca contas
if (isset($_POST['visualizar'])) {
    $sql = "SELECT * FROM conta";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $_SESSION['conta'] = $stmt->fetchAll();
}

// Definir filtro padrão
$filtro_status = isset($_GET['status']) ? $_GET['status'] : 'todas';

$user_id = $_SESSION['usuario']['id'];

// Busca o id do garçom
$sql_garcom = "SELECT id FROM garcom WHERE user_id = :user_id ";
$stmt = $conn->prepare($sql_garcom);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$dadosUsuario = $stmt->fetch();
$garcom_id = $dadosUsuario ? $dadosUsuario['id'] : null;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/conta.css">
</head>
<body>
     <div id="-logo-container">
        <img src="../img/logo.png" alt="">
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
            <button type="submit" name="visualizar" id="visualizar" class="btn btn-primary">Atualizar Lista de Contas</button>
            <a href="../abrir_conta.php" class="btn-voltar">Criar conta</a>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Mesa</th>
                    <th>Garçom ID</th>
                    <th>Data Abertura</th>
                    <th>Status</th>
                    <th>ID</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_SESSION['conta']) && !empty($_SESSION['conta'])) {
                    foreach ($_SESSION['conta'] as $conta) {
                        if ($filtro_status != 'todas' && $conta['status'] != $filtro_status) {
                            continue;
                        }

                        $mesa = $conta['mesa'] ?? '';
                        $garcom_id = $conta['garcom_id'] ?? '';
                        $data_abertura = $conta['data_abertura'] ?? '';
                        $status = $conta['status'] ?? '';
                        $id = $conta['id'] ?? 0;

                        $sql_produtos = "SELECT pd.id as pedido_id, p.nome, p.preco, pd.quantidade 
                                         FROM pedido pd
                                         JOIN produto p ON pd.produto_id = p.id
                                         WHERE pd.conta_id = :conta_id";
                        $stmt_produtos = $conn->prepare($sql_produtos);
                        $stmt_produtos->bindParam(':conta_id', $id);
                        $stmt_produtos->execute();
                        $produtos_conta = $stmt_produtos->fetchAll();
                ?>
                        <tr>
                            <td><?php echo $mesa; ?></td>
                            <td><?php echo $garcom_id; ?></td>
                            <td><?php echo $data_abertura; ?></td>
                            <td><?php echo ucfirst($status); ?></td>
                            <td><?php echo $id; ?></td>
                            <td>
                                <?php if ($status == 'aberta') { ?>
                                    <form method="post" action="../php/conta.php" style="display: inline;">
                                        <input type="hidden" name="conta_id" value="<?php echo $id; ?>">
                                        <button type="submit" name="fechar_conta" class="btn btn-warning" onclick="return confirm('Tem certeza que deseja fechar essa conta?')">Fechar Conta</button>
                                    </form>
                                    <form method="post" action="php/conta.php" style="display: inline;">
                                        <input type="hidden" name="conta_id" value="<?php echo $id; ?>">
                                        <button type="submit" name="cancelar_conta" class="btn btn-primary" onclick="return confirm('Tem certeza que deseja cancelar essa conta?')">Cancelar Conta</button>
                                    </form>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" style="padding: 0;">
                                <div class="produtos-conta">
                                    <strong>Produtos:</strong>
                                    <?php if (count($produtos_conta) > 0) {
                                        $total_conta = 0;
                                        foreach ($produtos_conta as $produto) {
                                            $subtotal = $produto['preco'] * $produto['quantidade'];
                                            $total_conta += $subtotal;
                                    ?>
                                            <div class="produto-item">
                                                <span>
                                                    <?php echo $produto['nome']; ?>
                                                    (<?php echo $produto['quantidade']; ?> x R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>)

                                                    <?php if ($status == 'aberta') { ?>
                                                        <form method="post" action="php/conta.php" style="display: inline;">
                                                            <input type="hidden" name="pedido_id" value="<?php echo $produto['pedido_id']; ?>">
                                                            <button type="submit" name="excluir_pedido" class="btn-excluir"
                                                                onclick="return confirm('Tem certeza que deseja excluir este item?')">
                                                                Excluir
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                </span>
                                                <span>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></span>
                                            </div>
                                    <?php }
                                    } else { ?>
                                        <div>Nenhum produto associado</div>
                                    <?php } ?>

                                    <?php if (isset($total_conta)) { ?>
                                        <div class="produto-item total-conta">
                                            <span>Total da Conta:</span>
                                            <span>R$ <?php echo number_format($total_conta, 2, ',', '.'); ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="7">Nenhum produto cadastrado</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>