<?php
require_once('conexao.php');
session_start();

// Cadastro de usuário
if(isset($_POST['cadastrar'])) {
    $conn = getConexao();
    
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $data_nasc = $_POST['data_nasc'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $cpf = $_POST['cpf'];
    
    // Verifica se usuário já existe - CORREÇÃO AQUI
    $sql = 'SELECT * FROM usuario WHERE email = :email OR cpf = :cpf';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();
    
    if($stmt->rowCount() == 0) {
        // Cadastra novo usuário - CORREÇÃO AQUI
        $sql = 'INSERT INTO usuario(nome, telefone, data_nasc, email, senha, cpf) 
                VALUES(:nome, :telefone, :data_nasc, :email, :senha, :cpf)';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':cpf', $cpf);
        
        if($stmt->execute()) {
            header('Location: ../login.php?sucesso=Cadastro realizado!');
        } else {
            header('Location: ../cadastro.php?erro=Erro no cadastro');
        }
    } else {
        header('Location: ../cadastro.php?erro=Usuário já existe');
    }
    exit();
}

// Login
if(isset($_POST['login'])) {
    $conn = getConexao();
    
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    // CORREÇÃO AQUI
    $sql = 'SELECT * FROM usuario WHERE email = :email';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if($stmt->rowCount() == 1) {
        $usuario = $stmt->fetch();
        
        if(password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email'],
                'tipo' => $usuario['tipo'] // 1-admin, 2-gerente, 3-garcom
            ];
            
            // Redireciona conforme o tipo
            if($usuario['tipo'] == 1) {
                header('Location: ../admin_dashboard.php');
            } elseif($usuario['tipo'] == 2) {
                header('Location: ../gerente_dashboard.php');
            } elseif($usuario['tipo'] == 3) {
                header('Location: ../garcom.php_dashboard');
            }
              else{
                header('Location: ../home.html');
              }
            exit();
        }
    }
    
}
?>