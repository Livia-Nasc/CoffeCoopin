<?php
    // Inicia a sessão e inclui o arquivo de conexão
    session_start();
    require('php/conexao.php');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoopinCoffe</title>
    <link rel="stylesheet" href="css/cardapio.css">
</head>
<body>
    <header class="header">
        <a href="index.html"><img src="img/logo.png" alt="Logo"></a>
    </header>

    <main class="menu-container">
        <?php
        $conn = getConexao();
        // Consulta as subcategorias de salgados
        $query = "SELECT s.id, s.nome 
                  FROM subcategoria s 
                  JOIN categoria c ON s.categoria_id = c.id 
                  WHERE c.nome = 'Salgados' 
                  ORDER BY s.id";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $subcategorias = $stmt->fetchAll();

        if (!empty($subcategorias)) {
            foreach ($subcategorias as $subcategoria) {
                // Consulta os produtos para cada subcategoria
                $queryProdutos = "SELECT nome, preco FROM produto 
                                 WHERE subcategoria_id = :subcategoria_id 
                                 ORDER BY nome";
                $stmtProdutos = $conn->prepare($queryProdutos);
                $stmtProdutos->bindParam(':subcategoria_id', $subcategoria['id']);
                $stmtProdutos->execute();
                $produtos = $stmtProdutos->fetchAll();
                
                if (!empty($produtos)) {
        ?>
                    <section class="card">
                        <h2><?php echo htmlspecialchars($subcategoria['nome']); ?></h2>
                        <ul>
                            <?php foreach ($produtos as $produto) { ?>
                                <li>
                                    <span class="nome-produto"><?php echo htmlspecialchars($produto['nome']); ?></span>
                                    <span class="preco">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                    </section>
        <?php
                }
            }
        } else {
            echo "<p>Nenhum salgado disponível no momento.</p>";
        }
        ?>
    </main>

    <footer class="footer">
        <p>☕</p>
    </footer>
</body>
</html>