<?php
require_once('php/conexao.php');
session_start();

// Verifica se o usuário está logado e é do tipo 3 (garçom)
if (($_SESSION['usuario']['tipo'] != 3)) {
    header('location:login.php');
    exit();
}

$conn = getConexao();

// Busca produtos
$sql = "SELECT id, nome, preco, categoria, porcao, qtd_estoque FROM produto";
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

$sql_garcom = "SELECT id FROM garcom WHERE user_id = :user_id ";
$stmt = $conn->prepare($sql_garcom);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
$garcom_id = $dadosUsuario['id'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Contas</title>
    <link rel="stylesheet" href="css/conta.css">
</head>

<body>
    <!-- Botão de Voltar -->
    <a href="garcom_dashboard.php" class="btn-voltar">Voltar</a>

    <div class="form-container">
        <h2>Abrir Nova Conta</h2>
        <form method="post" action="php/conta.php">
            <label for="mesa">Mesa</label>
            <input type="number" name="mesa" placeholder="Número da mesa" required>
            <input type="hidden" name="garcom_id" value="<?php echo $garcom_id ?>" required>
            <button type="submit" name="abrir_conta" class="btn btn-primary">Abrir Conta</button>
        </form>
    </div>

    <div class="filtro-container">
        <h3>Filtrar Contas</h3>
        <div>
            <a href="?status=todas" class="filtro-btn <?php echo $filtro_status == 'todas' ? 'active' : ''; ?>">Todas as Contas</a>
            <a href="?status=aberta" class="filtro-btn <?php echo $filtro_status == 'aberta' ? 'active' : ''; ?>">Contas Abertas</a>
            <a href="?status=fechada" class="filtro-btn <?php echo $filtro_status == 'fechada' ? 'active' : ''; ?>">Contas Fechadas</a>
            <a href="?status=cancelada" class="filtro-btn <?php echo $filtro_status == 'cancelada' ? 'active' : ''; ?>">Contas Canceladas</a>
        </div>
    </div>

    <form method="post">
        <button type="submit" name="visualizar" id="visualizar">Atualizar Lista de Contas</button>
    </form>

    <?php if (isset($_SESSION['produtos']) && isset($_SESSION['conta'])) { ?>
        <div class="form-container">
            <h3>Associar Produto a Conta</h3>
            <form method="post" action="php/conta.php">
                <select name="conta_id" required>
                    <option value="">Selecione a Conta</option>
                    <?php foreach ($_SESSION['conta'] as $conta) {
                        if ($conta['status'] == 'aberta') { ?>
                            <option value="<?php echo $conta['id']; ?>">
                                Mesa <?php echo $conta['mesa']; ?> (ID: <?php echo $conta['id']; ?>)
                            </option>
                    <?php }
                    } ?>
                </select>

                <select name="produto_id" required>
                    <option value="">Selecione o Produto</option>
                    <?php foreach ($_SESSION['produtos'] as $produto) { ?>
                        <option value="<?php echo $produto['id']; ?>">
                            <?php echo $produto['nome']; ?> - R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                        </option>
                    <?php } ?>
                </select>

                <input type="number" name="quantidade" placeholder="Quantidade" min="1" value="1" required>

                <button type="submit" name="associar_produto" class="associar-btn">Adicionar Produto</button>
            </form>
        </div>
    <?php } ?>

    <div id="produtos">
        <h2>Lista de Contas</h2>
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
                if (isset($_SESSION['conta'])) {
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
                                    <form method="post" action="php/conta.php" style="display: inline;">
                                        <input type="hidden" name="conta_id" value="<?php echo $id; ?>">
                                        <button type="submit" name="fechar_conta" class="fechar-btn">Fechar Conta</button>
                                    </form>
                                    <form method="post" action="php/conta.php" style="display: inline;">
                                        <input type="hidden" name="conta_id" value="<?php echo $id; ?>">
                                        <button type="submit" name="cancelar_conta" class="cancelar-btn">Cancelar Conta</button>
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
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>


    <script>
        // Confirmação antes de excluir
        document.querySelectorAll('.btn-excluir').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Tem certeza que deseja excluir este item?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>