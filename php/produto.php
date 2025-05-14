<?php
require_once('conexao.php');

function CadastrarProduto()
{
    session_start();
    $conn = getConexao();
    
    $nome = strtoupper($_POST['nome']);
    $preco = $_POST['preco'];
    $categoria_id = $_POST['categoria_id'];
    $subcategoria_id = isset($_POST['subcategoria_id']) ? $_POST['subcategoria_id'] : null;
    $porcao = $_POST['porcao'];
    $qtd_estoque = $_POST['qtd_estoque'];

    if (!empty($categoria_id)) {
        $sql = "INSERT INTO produto (nome, preco, categoria_id, subcategoria_id, porcao, qtd_estoque) 
                VALUES (:nome, :preco, :categoria_id, :subcategoria_id, :porcao, :qtd_estoque)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':categoria_id', $categoria_id);
        $stmt->bindParam(':subcategoria_id', $subcategoria_id);
        $stmt->bindParam(':porcao', $porcao);
        $stmt->bindParam(':qtd_estoque', $qtd_estoque);

        if ($stmt->execute()) {
            // Atualiza a lista de produtos com JOIN para pegar os nomes das categorias
            $sql = "SELECT p.id, p.nome, p.preco, c.nome as categoria, sc.nome as subcategoria, 
                    p.porcao, p.qtd_estoque 
                    FROM produto p
                    JOIN categoria c ON p.categoria_id = c.id
                    LEFT JOIN subcategoria sc ON p.subcategoria_id = sc.id";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $_SESSION['produto'] = $stmt->fetchAll();
            $_SESSION['mensagem'] = "Produto cadastrado com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao cadastrar produto!";
        }
    } else {
        $_SESSION['mensagem'] = "Categoria não selecionada";
    }
    header('Location: ../cadastro_produto.php');
    exit();
}

function VisualizarProduto()
{
    session_start();
    $conn = getConexao();
    $nome = strtoupper($_POST['nome'] ?? '');

    if ($nome != '') {
        $sql = "SELECT p.id, p.nome, c.nome as categoria, sc.nome as subcategoria, p.preco, p.porcao, p.qtd_estoque 
                FROM produto p
                JOIN categoria c ON p.categoria_id = c.id
                LEFT JOIN subcategoria sc ON p.subcategoria_id = sc.id
                WHERE p.nome LIKE :nome";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nome', "%$nome%");
        $stmt->execute();

        $_SESSION['produto'] = $stmt->fetchAll();
        $_SESSION['mensagem'] = $stmt->rowCount() ? "" : "Nenhum produto encontrado";
    } else {
        $sql = "SELECT p.id, p.nome, c.nome as categoria, sc.nome as subcategoria, p.preco, p.porcao, p.qtd_estoque 
                FROM produto p
                JOIN categoria c ON p.categoria_id = c.id
                LEFT JOIN subcategoria sc ON p.subcategoria_id = sc.id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $_SESSION['produto'] = $stmt->fetchAll();
    }
    header("Location: ../ver_produtos.php");
    exit();
}

function ExcluirProduto()
{
    session_start();
    $conn = getConexao();
    $id = $_POST['id'];
    
    // Primeiro exclui os pedidos relacionados
    $sqlPedidos = "DELETE FROM pedido WHERE produto_id = :id";
    $stmtPedidos = $conn->prepare($sqlPedidos);
    $stmtPedidos->bindParam(':id', $id);
    $stmtPedidos->execute();
    
    // Depois exclui o produto
    $sql = "DELETE FROM produto WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        // Atualiza a lista de produtos com as categorias e subcategorias
        $sql = "SELECT p.id, p.nome, p.preco, c.nome as categoria, sc.nome as subcategoria, 
                p.porcao, p.qtd_estoque 
                FROM produto p
                JOIN categoria c ON p.categoria_id = c.id
                LEFT JOIN subcategoria sc ON p.subcategoria_id = sc.id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $_SESSION['produto'] = $stmt->fetchAll();
        $_SESSION['mensagem'] = "Produto excluído com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao excluir produto!";
    }
    header('Location: ../visualizar_produto.php');
    exit();
}

function AlterarProduto()
{
    session_start();
    $conn = getConexao();
    $id = $_POST['id'];
    $nome = strtoupper($_POST['nome']);
    $preco = $_POST['preco'];
    $categoria_id = $_POST['categoria_id'];
    $subcategoria_id = isset($_POST['subcategoria_id']) ? $_POST['subcategoria_id'] : null;
    $porcao = $_POST['porcao'];
    $qtd_estoque = $_POST['qtd_estoque'];

    $sql = "UPDATE produto SET 
            nome = :nome, 
            preco = :preco, 
            categoria_id = :categoria_id, 
            subcategoria_id = :subcategoria_id, 
            porcao = :porcao, 
            qtd_estoque = :qtd_estoque 
            WHERE id = :id";
            
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':preco', $preco);
    $stmt->bindParam(':categoria_id', $categoria_id);
    $stmt->bindParam(':subcategoria_id', $subcategoria_id);
    $stmt->bindParam(':porcao', $porcao);
    $stmt->bindParam(':qtd_estoque', $qtd_estoque);

    if ($stmt->execute()) {
        // Atualiza a lista de produtos com as categorias e subcategorias
        $sql = "SELECT p.id, p.nome, p.preco, c.nome as categoria, sc.nome as subcategoria, 
                p.porcao, p.qtd_estoque 
                FROM produto p
                JOIN categoria c ON p.categoria_id = c.id
                LEFT JOIN subcategoria sc ON p.subcategoria_id = sc.id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $_SESSION['produto'] = $stmt->fetchAll();
        $_SESSION['mensagem'] = "Produto atualizado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar produto!";
    }
    header('Location: ../visualizar_produto.php');
    exit();
}

if (isset($_POST['cadastrar'])) {
    CadastrarProduto();
}

if (isset($_POST['visualizar'])) {
    VisualizarProduto();
}

if (isset($_POST['excluir'])) {
    ExcluirProduto();
}

if (isset($_POST['alterar'])) {
    AlterarProduto();
}