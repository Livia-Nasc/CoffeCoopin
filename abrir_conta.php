<?php
require_once('php/conexao.php');
session_start();

// Verifica se o usuário está logado e é do tipo 3 (garçom)
$tiposAcesso = [1,3];
$tipoUsuario = $_SESSION['usuario']['tipo'];
if (!in_array($tipoUsuario, $tiposAcesso)) {
    header('location:login.php');
    exit();
}
switch ($tipoUsuario) {
    case 1:
        $arquivo = 'dashboard/admin.php';
        break;
    case 3:
        $arquivo = 'dashboard/garcom.php';
        break;
}

$conn = getConexao();

// Busca produtos
$sql = "SELECT id, nome, preco, porcao, qtd_estoque FROM produto ORDER BY nome";
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

//
if($tipoUsuario == 3){
$sql_garcom = "SELECT id FROM garcom WHERE user_id = :user_id ";
$stmt = $conn->prepare($sql_garcom);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$dadosUsuario = $stmt->fetch();
$garcom_id = $dadosUsuario['id'];
};
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
    <div class="-logo-container">
        <img src="img/logo.png" alt="Logo" class="logo-img">
    </div>
    <!-- Botão de Voltar -->
    <a href="<?php echo $arquivo?>" class="btn-voltar">Voltar</a>

    <div class="form-container">
        <h2>Abrir Nova Conta</h2>
        <form method="post" action="php/conta.php">
            <label for="mesa">Mesa</label>
            <input type="number" name="mesa" placeholder="Número da mesa" required>
            <input type="hidden" name="garcom_id" value="<?php echo $garcom_id ?>" required>
            <button type="submit" name="abrir_conta" class="btn btn-primary">Abrir Conta</button>
            <a href="visualização/conta.php" class="btn-voltar">Visualizar contas</a>
        </form>
    </div>

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

                <button type="submit" name="associar_produto" class="btn btn-primary">Adicionar Produto</button>
            </form>
        </div>
</body>
</html>