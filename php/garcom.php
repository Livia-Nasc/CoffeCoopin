<?php
require_once('conexao.php');
session_start();

// Verifica se é garçom
if(!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 3) {
    header('Location: ../login.php');
    exit();
}

// Atualizar perfil do garçom
if(isset($_POST['atualizar_perfil'])) {
    $conn = getConexao();
    
    $dados = [
        'id' => $_SESSION['usuario']['id'],
        'nome' => $_POST['nome'],
        'telefone' => $_POST['telefone'],
        'email' => $_POST['email'],
        'escolaridade' => $_POST['escolaridade']
    ];
    
    // Atualiza usuário
    $sql = "UPDATE usuario SET nome = ?, telefone = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $usuarioAtualizado = $stmt->execute([$dados['nome'], $dados['telefone'], $dados['email'], $dados['id']]);
    
    // Atualiza garçom
    $sql = "UPDATE garcom SET escolaridade = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $garcomAtualizado = $stmt->execute([$dados['escolaridade'], $dados['id']]);
    
    if($usuarioAtualizado && $garcomAtualizado) {
        // Atualiza sessão
        $_SESSION['usuario']['nome'] = $dados['nome'];
        $_SESSION['usuario']['email'] = $dados['email'];
        $_SESSION['usuario']['escolaridade'] = $dados['escolaridade'];
        
        header('Location: ../garcom.php?sucesso=Perfil atualizado!');
    } else {
        header('Location: ../garcom.php?erro=Erro ao atualizar perfil');
    }
    exit();
}
?>