<?php
require_once('conexao.php');
session_start();

// Verifica se é garçom
if($_SESSION['usuario']['tipo'] != 3) {
    header('Location: ../login.php');
    exit();
}

// Adicionar pedido
if(isset($_POST['adicionar'])) {
    $conn = getConexao();
    
    $conta_id = $_POST['conta_id'];
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];
    
    $sql = "INSERT INTO pedido (conta_id, produto_id, quantidade, data_hora) 
            VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$conta_id, $produto_id, $quantidade])) {
        header('Location: ../garcom.php?sucesso=Pedido adicionado!');
    } else {
        header('Location: ../garcom.php?erro=Erro ao adicionar pedido');
    }
    exit();
}

// Remover pedido
if(isset($_POST['remover'])) {
    $conn = getConexao();
    $pedido_id = $_POST['pedido_id'];
    
    $sql = "DELETE FROM pedido WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$pedido_id])) {
        header('Location: ../garcom.php?sucesso=Pedido removido!');
    } else {
        header('Location: ../garcom.php?erro=Erro ao remover pedido');
    }
    exit();
}
?>