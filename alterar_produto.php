<?php
session_start();
if (!isset($_POST['id'])) {
    header('Location: cadastro_produto.php');
    exit();
}

$id = $_POST['id'];
$nome = strtoupper($_POST['nome'] ?? '');
$preco = $_POST['preco'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$porcao = $_POST['porcao'] ?? '';
$qtd_estoque = $_POST['qtd_estoque'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar produto</title>
    <link rel="stylesheet" href="css/alterar_produto.css">
</head>

<body>
    <div class="form-container">
        <h2>Alterar Produto</h2>

        <form action="php/produto.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <label>Nome:
                <input type="text" name="nome" value="<?php echo $nome; ?>" required>
            </label>

            <label>Preço:
                <input type="number" step="0.01" name="preco" value="<?php echo $preco; ?>" required>
            </label>

            <label>Categoria:
                <select name="categoria" required>
                    <option value="Bebida" <?php echo $categoria == 'Bebida' ? 'selected' : ''; ?>>Bebida</option>
                    <option value="Comida" <?php echo $categoria == 'Comida' ? 'selected' : ''; ?>>Comida</option>
                </select>
            </label>

            <label>Porção:
                <select name="porcao" required>
                    <option value="grande" <?php echo $porcao == 'grande' ? 'selected' : ''; ?>>Grande</option>
                    <option value="media" <?php echo $porcao == 'media' ? 'selected' : ''; ?>>Média</option>
                    <option value="pequena" <?php echo $porcao == 'pequena' ? 'selected' : ''; ?>>Pequena</option>
                </select>
            </label>

            <label>Quantidade em Estoque:
                <input type="number" name="qtd_estoque" value="<?php echo $qtd_estoque; ?>" required>
            </label>

            <button type="submit" name="alterar">Salvar</button>
            <a href="cadastro_produto.php" class="cancelar-btn">Cancelar</a>
        </form>
    </div>
</body>

</html>