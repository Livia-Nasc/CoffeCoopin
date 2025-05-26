<?php
session_start();
$tiposAcesso = [1,2,3];
$tipoUsuario = $_SESSION['usuario']['tipo'];
if (!in_array($tipoUsuario, $tiposAcesso)) {
    header('location:../login.php');
    exit();
}
switch ($tipoUsuario) {
    case 1:
        $arquivo = 'dashboard/admin.php';
        break;
    case 2:
        $arquivo = 'dashboard/gerente.php';
        break;
    case 3:
        $arquivo = 'dashboard/garcom.php';
        break;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Produtos</title>
    <link rel="stylesheet" href="../css/cadastro_produto.css">
</head>

<body>
    <div id="-logo-container">
        <img src="../img/logo.png" alt="">
    </div>
    <!-- Botão de Voltar -->
    <a href="<?php echo $arquivo?>" class="btn-voltar">Voltar</a>

    <div id="produtos">
         <?php if ($_SESSION['usuario']['tipo'] != 3) { ?>
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="../cadastro/produto.php" class="btn btn-primary">Cadastrar Novo Produto</a>
        </div>
    <?php }?>

        <form action="../php/produto.php" method="post">
            <label for="nome">Pesquisar produto</label> 
            <input type="text" name="nome" placeholder="Insira o nome do produto" id="nome">
            <button type="submit" name="visualizar" id="visualizar">Visualizar produtos</button>
        </form>

        <?php if(isset($_SESSION['mensagem'])) { ?>
            <div class="mensagem"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
        <?php } ?>

        <table>
            <tr>
                <th>Nome</th>
                <th>Porção</th>
                <th>Estoque</th>
                <th>Categoria</th>
                <th>Subcategoria</th>
                <th>Preço</th>
                <?php if ($_SESSION['usuario']['tipo'] != 3) { ?>
                <th>Ações</th>
                <?php } ?>
            </tr>
            <?php if(isset($_SESSION['produto']) && !empty($_SESSION['produto'])) { ?>
                <?php foreach($_SESSION['produto'] as $produto) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                        <td><?php echo htmlspecialchars($produto['porcao']); ?></td>
                        <td><?php echo htmlspecialchars($produto['qtd_estoque']); ?></td>
                        <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                        <td><?php echo htmlspecialchars($produto['subcategoria']); ?></td>
                        <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                        
                                <?php if ($_SESSION['usuario']['tipo'] == 2) { ?>
                        <td>
                            <form action="alterar_produto.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                                <input type="hidden" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>">
                                <input type="hidden" name="preco" value="<?php echo $produto['preco']; ?>">
                                <input type="hidden" name="categoria_id" value="<?php echo $produto['categoria']; ?>">
                                <input type="hidden" name="subcategoria_id" value="<?php echo $produto['subcategoria'] ?? ''; ?>">
                                <input type="hidden" name="porcao" value="<?php echo htmlspecialchars($produto['porcao']); ?>">
                                <input type="hidden" name="qtd_estoque" value="<?php echo $produto['qtd_estoque']; ?>">
                                <button type="submit" name="alterar" class="btn-alterar">Alterar</button>
                                <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                                <button type="submit" name="excluir" class="btn btn-primary">Excluir</button>
                                <?php } ?>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="7">Nenhum produto cadastrado</td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>