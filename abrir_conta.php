<?php
require_once('php/conexao.php');
session_start();

// Verifica se o usuário está logado e é do tipo 1 ou 3 (admin ou garçom)
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
$user_id = $_SESSION['usuario']['id'];

// Busca id do garçom
$garcom_id = null;
if($tipoUsuario == 3){
    $sql_garcom = "SELECT id FROM garcom WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql_garcom);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $dadosUsuario = $stmt->fetch();
    $garcom_id = $dadosUsuario['id'];
}

// Busca produtos
    $sql = "SELECT id, nome, preco, porcao, qtd_estoque FROM produto ORDER BY nome";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $_SESSION['produtos'] = $stmt->fetchAll();

// Busca contas abertas para o garçom
$contas_abertas = [];
if ($garcom_id) {
    $sql_contas = "SELECT id, mesa FROM conta WHERE garcom_id = :garcom_id AND status = 'aberta' ORDER BY data_abertura DESC";
    $stmt_contas = $conn->prepare($sql_contas);
    $stmt_contas->bindParam(':garcom_id', $garcom_id);
    $stmt_contas->execute();
    $contas_abertas = $stmt_contas->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrir Conta</title>
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
            <label for="mesa">Número da Mesa</label>
            <input type="number" name="mesa" min="1" required>
            
            <input type="hidden" name="garcom_id" value="<?php echo $garcom_id ?>">
            
            <button type="submit" name="abrir_conta" class="btn btn-primary">Abrir Conta</button>
            <a href="visualização/conta.php" class="btn-voltar">Visualizar Contas</a>
        </form>
    </div>

    <?php if (!empty($contas_abertas)){ ?>
        <div class="form-container">
            <h3>Adicionar Produto a Conta Aberta</h3>
            <form method="post" action="php/conta.php">
                <label for="conta_id">Selecione a Conta</label>
                <select name="conta_id" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach ($contas_abertas as $conta){ ?>
                        <option value="<?php echo $conta['id']; ?>">
                            Mesa <?php echo $conta['mesa']; ?> (ID: <?php echo $conta['id']; ?>)
                        </option>
                    <?php }?>
                </select>

                <label for="produto_id">Selecione o Produto</label>
                <select name="produto_id" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach ($_SESSION['produtos'] as $produto){ ?>
                    <option value="<?= $produto['id'] ?>">
                        <?php echo htmlspecialchars($produto['nome']); ?> 
                            - R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                            (Estoque: <?php echo $produto['qtd_estoque']; ?>)
                        </option>
                    <?php } ?>
                </select>

                <label for="quantidade">Quantidade</label>
                <input type="number" name="quantidade" min="1" value="1" required>

                <button type="submit" name="associar_produto" class="btn btn-primary">Adicionar Produto</button>
            </form>
        </div>
    <?php } ?>
</body>
</html>