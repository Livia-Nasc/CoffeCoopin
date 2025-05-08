<?php
session_start();
    if ($_SESSION['usuario']['tipo'] != 3 && $_SESSION['usuario']['tipo'] != 2){
        header('location:login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Produtos</title>
    <link rel="stylesheet" href="css/cadastro_produto.css">
</head>

<body>
    <a href="garcom_dashboard.php" class="btn-voltar">Voltar</a>

    <div id="produtos">
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="cadastro_produto.php" class="btn-cadastro">Cadastrar Novo Produto</a>
        </div>

        <form action="php/produto.php" method="post">
            <label for="nome">Pesquisar produto</label>
            <input type="text" name="nome" placeholder="Insira o nome do produto" id="nome">
            <button type="submit" name="visualizar" id="visualizar">Visualizar produtos</button>
        </form>

        <table>
            <tr>
                <th>Nome</th>
                <th>Porção</th>
                <th>Qtd Estoque</th>
                <th>Categoria</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
            <?php
            if (isset($_SESSION['produto'])) {
                foreach ($_SESSION['produto'] as $produto) {
                    $nome = $produto['nome'] ?? '';
                    $porcao = $produto['porcao'] ?? '';
                    $qtd_estoque = $produto['qtd_estoque'] ?? 0;
                    $categoria = $produto['categoria'] ?? '';
                    $preco = $produto['preco'] ?? 0.0;
            ?>
                    <tr>
                        <td><?php echo $nome; ?></td>
                        <td><?php echo $porcao; ?></td>
                        <td><?php echo $qtd_estoque; ?></td>
                        <td><?php echo $categoria; ?></td>
                        <td><?php echo $preco; ?></td>
                        <td>
                            <form action="alterar_produto.php" method="post" style="display:inline;">
                                <input type="hidden" name="nome" value="<?php echo $nome; ?>">
                                <input type="hidden" name="preco" value="<?php echo $preco; ?>">
                                <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">
                                <input type="hidden" name="porcao" value="<?php echo $porcao; ?>">
                                <input type="hidden" name="qtd_estoque" value="<?php echo $qtd_estoque; ?>">
                                <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                                <button type="submit" name="alterar" class="btn btn-primary btn-sm">Alterar</button>
                            </form>

                            <form action="php/produto.php" method="post" style="display:inline;">
                                <input type="hidden" name="nome" value="<?php echo $nome; ?>">
                                <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                                <button type="submit" name="excluir" class="btn btn-primary btn-sm">Excluir</button>
                            </form>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>