<?php
session_start();
if ($_SESSION['usuario']['tipo'] != 2) {
    header('location:login.php');
    exit();
}

require_once 'php/conexao.php';
$conn = getConexao();

// Pegar todas as categorias
$stmtCategorias = $conn->query("SELECT id, nome FROM categoria");
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

$categoriaSelecionada = isset($_POST['categoria_id']) ? $_POST['categoria_id'] : (isset($_GET['categoria_id']) ? $_GET['categoria_id'] : null);

$subcategorias = [];
if ($categoriaSelecionada) {
    $stmtSubcategorias = $conn->prepare("SELECT id, nome FROM subcategoria WHERE categoria_id = ?");
    $stmtSubcategorias->execute([$categoriaSelecionada]);
    $subcategorias = $stmtSubcategorias->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="css/conta.css">
</head>

<body>
    <div class="-logo-container">
        <img src="img/logo.png" alt="Logo" class="logo-img">
    </div>
    <a href="gerente_dashboard.php" class="btn-voltar">Voltar</a>
    
    <div id="container">
        <div id="box">
            <div class="cadastro">
                <form action="php/produto.php" method="post" class="form-container" id="formProduto">
                    <h2>CADASTRAR PRODUTO</h2>
                    <br>
                    
                    <label for="nome"> Nome do produto </label>
                        <input type="text" id="nome" required name="nome" placeholder="Insira o nome do produto"
                               value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>">
                    
                    <br>
                    
                    <label for="preco">Preço do produto </label>
                        <input type="text" id="preco" required name="preco" placeholder="Insira o preço"
                               value="<?php echo isset($_POST['preco']) ? htmlspecialchars($_POST['preco']) : ''; ?>">
                    <br>
                    <label for="categoria">Categoria do produto</label>
                    <select name="categoria_id" id="categoria" required onchange="this.form.submit()">
                        <option value="">Escolha</option>
                        <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?php echo $categoria['id']; ?>"
                                <?php echo ($categoriaSelecionada == $categoria['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($categoria['nome']); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <br>
                    <label for="subcategoria">Subcategoria do produto</label>
                    <select name="subcategoria_id" id="subcategoria" required <?php echo empty($subcategorias) ? 'disabled' : ''; ?>>
                        <option value=""><?php echo empty($subcategorias) ? 'Escolha' : 'Escolha a subcategoria'; ?></option>
                        <?php foreach ($subcategorias as $subcategoria) { ?>
                            <option value="<?php echo $subcategoria['id']; ?>"
                                <?php echo isset($_POST['subcategoria_id']) && $_POST['subcategoria_id'] == $subcategoria['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($subcategoria['nome']); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <br>
                    <label for="porcao">Porção do produto</label>
                    <select name="porcao" id="porcao" required>
                        <option value="">Escolha</option>
                        <option value="grande" <?php echo isset($_POST['porcao']) && $_POST['porcao'] == 'grande' ? 'selected' : ''; ?>>Grande</option>
                        <option value="media" <?php echo isset($_POST['porcao']) && $_POST['porcao'] == 'media' ? 'selected' : ''; ?>>Média</option>
                        <option value="pequena" <?php echo isset($_POST['porcao']) && $_POST['porcao'] == 'pequena' ? 'selected' : ''; ?>>Pequena</option>
                    </select>
                    <br>
                    
                    <label for="qtd_estoque">Quantidade de estoque</label>
                        <input type="text" id="qtd_estoque" required name="qtd_estoque" placeholder="Insira o estoque"
                               value="<?php echo isset($_POST['qtd_estoque']) ? htmlspecialchars($_POST['qtd_estoque']) : ''; ?>">
                    
                    <br>
                    
                    <div id="btn">
                        <button type="submit" name="cadastrar" class="btn btn-primary">ENVIAR</button>
                        <a href="visualizar_produto.php"><button type="button" class="btn btn-primary">Visualizar Produtos</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if(isset($_SESSION['mensagem'])) { ?>
        <div class="mensagem-alerta">
            <?php 
                echo $_SESSION['mensagem'];
                unset($_SESSION['mensagem']); 
            ?>
        </div>
    <?php } ?>

    <script>
        // Função mínima apenas para submeter o formulário quando a categoria muda
        document.getElementById('categoria').onchange = function() {
            document.getElementById('formProduto').action = 'cadastro_produto.php';
            document.getElementById('formProduto').submit();
        };
    </script>
</body>
</html>