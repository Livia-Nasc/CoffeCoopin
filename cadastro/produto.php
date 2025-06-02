<?php
session_start();
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

require_once '../php/conexao.php';
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
    <link rel="stylesheet" href="../css/conta.css">
</head>

<body>
    <div class="-logo-container">
        <img src="../img/logo.png" alt="Logo" class="logo-img">
    </div>
    <a href="<?php echo $arquivo?>" class="btn-voltar">Voltar</a>
    
    <div id="container">
        <div id="box">
            <div class="cadastro">
                <form action="../php/produto.php" method="post" class="form-container" id="formProduto">
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
                        <option value="Grande" <?php echo isset($_POST['porcao']) && $_POST['porcao'] == 'Grande' ? 'selected' : ''; ?>>Grande</option>
                        <option value="Média" <?php echo isset($_POST['porcao']) && $_POST['porcao'] == 'Média' ? 'selected' : ''; ?>>Média</option>
                        <option value="Pequena" <?php echo isset($_POST['porcao']) && $_POST['porcao'] == 'Pequena' ? 'selected' : ''; ?>>Pequena</option>
                    </select>
                    <br>
                    
                    <label for="qtd_estoque">Quantidade de estoque</label>
                        <input type="text" id="qtd_estoque" required name="qtd_estoque" placeholder="Insira o estoque"
                               value="<?php echo isset($_POST['qtd_estoque']) ? htmlspecialchars($_POST['qtd_estoque']) : ''; ?>">
                    
                    <br>
                     <?php if(isset($_SESSION['mensagem'])) { ?>
                        <div class="mensagem-alerta">
                            <?php 
                                echo $_SESSION['mensagem'];
                                unset($_SESSION['mensagem']); 
                            ?>
                        </div>
                    <?php } ?>
                    
                    <div id="btn">
                        <button type="submit" name="cadastrar" class="btn btn-primary">ENVIAR</button>
                        <a href="../visualização/produtos.php"><button type="button" class="btn btn-primary">Visualizar Produtos</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Função mínima apenas para submeter o formulário quando a categoria muda
        document.getElementById('categoria').onchange = function() {
            document.getElementById('formProduto').action = 'produto.php';
            document.getElementById('formProduto').submit();
        };
    </script>
</body>
</html>