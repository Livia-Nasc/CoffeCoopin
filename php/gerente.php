<?php
require_once('conexao.php');
session_start();

// Verifica se é admin
if($_SESSION['usuario']['tipo'] != 1) {
    header('Location: ../login.php');
    exit();
}

// Cadastrar gerente
if(isset($_POST['cadastrar_gerente'])) {
    $conn = getConexao();
    
    $dados = [
        'nome' => $_POST['nome'],
        'cpf' => $_POST['cpf'],
        'rg' => $_POST['rg'],
        'email' => $_POST['email'],
        'telefone' => $_POST['telefone'],
        'data_nasc' => $_POST['data_nasc'],
        'senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT),
        'tipo' => 2 // Tipo gerente
    ];
    
    // Verifica se já existe
    $sql = 'SELECT * FROM usuario WHERE email = ? OR cpf = ?';
    $stmt = $conn->prepare($sql);
    $stmt->execute([$dados['email'], $dados['cpf']]);
    
    if($stmt->rowCount() == 0) {
        // Cadastra usuário
        $sql = 'INSERT INTO usuario (nome, cpf, email, telefone, data_nasc, senha, tipo) 
                VALUES (?, ?, ?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($sql);
        
        if($stmt->execute([
            $dados['nome'], 
            $dados['cpf'], 
            $dados['email'], 
            $dados['telefone'], 
            $dados['data_nasc'], 
            $dados['senha'], 
            $dados['tipo']
        ])) {
            $user_id = $conn->lastInsertId();
            
            // Cadastra como gerente
            $sql = 'INSERT INTO gerente (user_id, rg) VALUES (?, ?)';
            $stmt = $conn->prepare($sql);
            
            if($stmt->execute([$user_id, $dados['rg']])) {
                header('Location: ../admin.php?sucesso=Gerente cadastrado!');
                exit();
            }
        }
    }
    
    header('Location: ../cadastro_gerente.php?erro=Erro ao cadastrar gerente');
    exit();
}
?>