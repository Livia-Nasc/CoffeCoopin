<?php
session_start();
// Verifica se o usuário está logado e é do tipo 2 (gerente)
if (!isset($_POST['id']) || $_SESSION['usuario']['tipo'] != 2) {
    header('Location: cadastro_produto.php');
    exit();
}

require_once 'php/conexao.php';
$conn = getConexao();

$id = $_POST['id'];

// Buscar os dados atuais do produto
$stmtProduto = $conn->prepare("SELECT p.*, c.nome as categoria_nome, sc.nome as subcategoria_nome 
                             FROM produto p
                             JOIN categoria c ON p.categoria_id = c.id
                             LEFT JOIN subcategoria sc ON p.subcategoria_id = sc.id
                             WHERE p.id = ?");
$stmtProduto->execute([$id]);
$produto = $stmtProduto->fetch();

if (!$produto) {
    header('Location: visualizar_produto.php');
    exit();
}

// Atribuir valores atuais
$nome = strtoupper($produto['nome'] ?? '');
$preco = $produto['preco'] ?? '';
$categoria_id = $produto['categoria_id'] ?? '';
$subcategoria_id = $produto['subcategoria_id'] ?? '';
$porcao = $produto['porcao'] ?? '';
$qtd_estoque = $produto['qtd_estoque'] ?? '';

// Buscar todas as categorias disponíveis
$stmtCategorias = $conn->query("SELECT id, nome FROM categoria");
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

// Buscar subcategorias da categoria atual
$subcategorias = [];
if ($categoria_id) {
    $stmtSubcategorias = $conn->prepare("SELECT id, nome FROM subcategoria WHERE categoria_id = ?");
    $stmtSubcategorias->execute([$categoria_id]);
    $subcategorias = $stmtSubcategorias->fetchAll(PDO::FETCH_ASSOC);
}

// Se for uma requisição para atualizar subcategorias
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoria_id']) && !isset($_POST['alterar'])) {
    $categoria_id = $_POST['categoria_id'];
    $stmtSubcategorias = $conn->prepare("SELECT id, nome FROM subcategoria WHERE categoria_id = ?");
    $stmtSubcategorias->execute([$categoria_id]);
    $subcategorias = $stmtSubcategorias->fetchAll(PDO::FETCH_ASSOC);
    
    // Preencher o select de subcategorias
    echo '<option value="">Selecione</option>';
    foreach ($subcategorias as $subcategoria) {
        $selected = ($subcategoria_id == $subcategoria['id']) ? 'selected' : '';
        echo '<option value="'.$subcategoria['id'].'" '.$selected.'>'.htmlspecialchars($subcategoria['nome']).'</option>';
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar produto</title>
    <link rel="stylesheet" href="css/conta.css">
</head>

<body>
    <div id="-logo-container">
        <img src="img/Group 1.png" alt="">
    </div>
    
    <div id="container">
        <div id="box">
            <div class="cadastro">
                <form action="php/produto.php" method="post" class="form-container" id="formProduto">
                    <h2>ALTERAR PRODUTO</h2>
                    <br>
                    
                    <input type="hidden" name="id" value="<?php echo $id; ?>">

                    <label for="nome">Nome do produto</label>
                        <input type="text" id="nome" name="nome" required placeholder="Insira o nome do produto"
                               value="<?php echo htmlspecialchars($nome); ?>">
                    
                    <br>
                    
                    <label for="preco">Preço do produto</label>
                        <input type="number" step="0.01" id="preco" name="preco" required placeholder="Insira o preço"
                               value="<?php echo htmlspecialchars($preco); ?>">
                    
                    <br>
                    
                    <label for="categoria">Categoria do produto</label>
                    <select name="categoria_id" id="categoria" required onchange="this.form.submit()">
                        <option value="">Escolha</option>
                        <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?php echo $categoria['id']; ?>"
                                <?php echo ($categoria_id == $categoria['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($categoria['nome']); ?>
                            </option>
                        <?php } ?>
                    </select>
                    
                    <br>
                    
                    <label for="subcategoria">Subcategoria do produto</label>
                    <select name="subcategoria_id" id="subcategoria" <?php echo empty($subcategorias) ? 'disabled' : ''; ?>>
                        <option value=""><?php echo empty($subcategorias) ? 'Escolha' : 'Escolha a subcategoria'; ?></option>
                        <?php foreach ($subcategorias as $subcategoria) { ?>
                            <option value="<?php echo $subcategoria['id']; ?>"
                                <?php echo ($subcategoria_id == $subcategoria['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($subcategoria['nome']); ?>
                            </option>
                        <?php } ?>
                    </select>
                    
                    <br>
                    
                    <label for="porcao">Porção do produto</label>
                    <select name="porcao" id="porcao" required>
                        <option value="">Escolha</option>
                        <option value="grande" <?php echo $porcao == 'grande' ? 'selected' : ''; ?>>Grande</option>
                        <option value="media" <?php echo $porcao == 'media' ? 'selected' : ''; ?>>Média</option>
                        <option value="pequena" <?php echo $porcao == 'pequena' ? 'selected' : ''; ?>>Pequena</option>
                    </select>
                    
                    <br>
                    
                    <label for="qtd_estoque">Quantidade de estoque</label>
                        <input type="number" id="qtd_estoque" name="qtd_estoque" min="0" required placeholder="Insira o estoque"
                               value="<?php echo htmlspecialchars($qtd_estoque); ?>">
                    
                    <br>
                    
                    <div id="btn">
                        <button type="submit" name="alterar" class="btn btn-primary">SALVAR</button>
                        <a href="visualizar_produto.php"><button type="button" class="btn btn-primary">CANCELAR</button></a>
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
        document.getElementById('categoria').onchange = function() {
            document.getElementById('formProduto').action = 'alterar_produto.php';
            document.getElementById('formProduto').submit();
        };
    </script>
</body>
</html>