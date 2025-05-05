<?php
require_once('conexao.php');

function CadastrarProduto()
{
    session_start();
    $conn = getConexao();
    $nome = strtoupper($_POST['nome']);
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];
    $porcao = $_POST['porcao'];
    $qtd_estoque = $_POST['qtd_estoque'];

    if (!empty($categoria) && !empty($porcao)) {
        $sql = "INSERT INTO produto (nome, preco, categoria, porcao, qtd_estoque) VALUES (:nome, :preco, :categoria, :porcao, :qtd_estoque)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':porcao', $porcao);
        $stmt->bindParam(':qtd_estoque', $qtd_estoque);

        if ($stmt->execute()) {
            // Atualiza a lista de produtos
            $sql = "SELECT id, nome, preco, categoria, porcao, qtd_estoque FROM produto";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $_SESSION['produto'] = $stmt->fetchAll();
            $_SESSION['mensagem'] = "Produto cadastrado com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao cadastrar produto!";
        }
    } else {
        $_SESSION['mensagem'] = "Escolha não selecionada";
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
        $sql = "SELECT id, nome, preco, categoria, porcao, qtd_estoque FROM produto WHERE nome LIKE :nome";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nome', "%$nome%");
        $stmt->execute();

        $_SESSION['produto'] = $stmt->fetchAll();
        $_SESSION['mensagem'] = $stmt->rowCount() ? "" : "Nenhum produto encontrado";
    } else {
        $sql = "SELECT id, nome, preco, categoria, porcao, qtd_estoque FROM produto";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $_SESSION['produto'] = $stmt->fetchAll();
    }
    header("Location: ../visualizar_produto.php");
    exit();
}

function ExcluirProduto()
{
    session_start();
    $conn = getConexao();
    $id = $_POST['id'];
    $sqlPedidos = "DELETE FROM pedido WHERE produto_id = :id";
    $stmtPedidos = $conn->prepare($sqlPedidos);
    $stmtPedidos->bindParam(':id', $id);
    $stmtPedidos->execute();
    $sql = "DELETE FROM produto WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        // Atualiza a lista de produtos
        $sql = "SELECT id, nome, preco, categoria, porcao, qtd_estoque FROM produto";
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
    $categoria = $_POST['categoria'];
    $porcao = $_POST['porcao'];
    $qtd_estoque = $_POST['qtd_estoque'];

    $sql = "UPDATE produto SET nome = :nome, preco = :preco, categoria = :categoria, porcao = :porcao, qtd_estoque = :qtd_estoque WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':preco', $preco);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':porcao', $porcao);
    $stmt->bindParam(':qtd_estoque', $qtd_estoque);

    if ($stmt->execute()) {
        // Atualiza a lista de produtos
        $sql = "SELECT id, nome, preco, categoria, porcao, qtd_estoque FROM produto";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $_SESSION['produto'] = $stmt->fetchAll();
        $_SESSION['mensagem'] = "Produto atualizado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar produto!";
    }
    header('Location: ../cadastro_produto.php');
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
