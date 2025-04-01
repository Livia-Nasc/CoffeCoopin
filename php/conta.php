<?php
require_once('conexao.php');
session_start();

// Verifica se é garçom
if($_SESSION['usuario']['tipo'] != 3) {
    header('Location: login.php');
    exit();
}

// Abrir conta
if(isset($_POST['abrir'])) {
    $conn = getConexao();
    $mesa = $_POST['mesa'];
    $garcom_id = $_SESSION['usuario']['id'];
    
    $sql = "INSERT INTO conta (mesa, garcom_id, data_abertura, status) 
            VALUES (?, ?, NOW(), 'aberta')";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$mesa, $garcom_id])) {
        header('Location: garcom.php?sucesso=Conta aberta!');
    } else {
        header('Location: garcom.php?erro=Erro ao abrir conta');
    }
    exit();
}

// Fechar conta
if(isset($_POST['fechar'])) {
    $conn = getConexao();
    $conta_id = $_POST['conta_id'];
    
    $sql = "UPDATE conta SET status = 'fechada', data_fechamento = NOW() 
            WHERE id = ? AND garcom_id = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$conta_id, $_SESSION['usuario']['id']])) {
        header('Location: garcom.php?sucesso=Conta fechada!');
    } else {
        header('Location: garcom.php?erro=Erro ao fechar conta');
    }
    exit();
}
?>