<?php
require_once('conexao.php');
session_start();

$tiposAcesso = [1,2];
$tipoUsuario = $_SESSION['usuario']['tipo'];
switch ($tipoUsuario) {
    case 1:
        $arquivo = '../dashboard/admin.php';
        break;
    case 2:
        $arquivo = '../dashboard/gerente.php';
        break;
}

function CadastrarGarcom() {
    $conn = getConexao();

    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $data_nasc = $_POST['data_nasc'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $cpf = $_POST['cpf'];
    $escolaridade = $_POST['escolaridade'];

    $sql = 'SELECT * FROM usuario WHERE cpf = :cpf OR email = :email';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $cadastro = 'INSERT INTO usuario(nome, telefone, data_nasc, email, senha, cpf, tipo) 
                     VALUES(:nome, :telefone, :data_nasc, :email, :senha, :cpf, 3)';
        $stmt = $conn->prepare($cadastro);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cpf', $cpf);

        if ($stmt->execute()) {
            $user_id = $conn->lastInsertId();

            $cadastroGarcom = 'INSERT INTO garcom(escolaridade, user_id) VALUES(:escolaridade, :user_id)';
            $stmt = $conn->prepare($cadastroGarcom);
            $stmt->bindParam(':escolaridade', $escolaridade);
            $stmt->bindParam(':user_id', $user_id);
            
            if ($stmt->execute()) {
                header('location: ../visualização/garcons.php'); // ! Vai para a página de login
                exit();
            }
        }
        echo "Erro ao cadastrar usuário.";
    } else {
        echo "<script type='text/javascript'>
                alert('Informações já existentes');
                window.location='../cadastro/garcom.php';
              </script>";
    }
}

function VisualizarGarcom() {
    $nome = strtoupper($_POST['nome'] ?? '');
    $conn = getConexao();
if (!empty($nome)) {
        // Se o nome for informado, busca os garçons com base no nome
        $nome = strtoupper($nome);
        
        // Consulta para buscar garçons com o nome informado
    $sql = "SELECT g.id, u.nome, u.cpf, u.email, u.telefone, u.data_nasc, g.escolaridade, 
            (SELECT COUNT(*) FROM conta WHERE garcom_id = g.id) as contas_gerenciadas
            FROM usuario u 
            JOIN garcom g 
            ON u.id = g.user_id
            WHERE u.nome LIKE :nome" ; // Tipo 3 = Garçom
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nome', "%$nome%");
        $stmt->execute();
        
        $_SESSION['garcom'] = $stmt->fetchAll();
        $_SESSION['mensagem'] = $stmt->rowCount() ? "" : "Nenhum garçom encontrado";
    } else{
        $conn = getConexao();
        $sql = "SELECT g.id, u.nome, u.cpf, u.email, u.telefone, u.data_nasc, g.escolaridade 
            FROM usuario u 
            JOIN garcom g 
            ON u.id = g.user_id
            WHERE u.tipo = 3"; // Tipo 3 = Garçom
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $_SESSION['garcom'] = $stmt->fetchAll();
        
    }
    header('location: ../visualização/garcons.php'); 
    exit();
}

// Processamento das ações
if (isset($_POST['cadastrar_garcom'])) {
    CadastrarGarcom();
}

if (isset($_POST['visualizar'])) {
    VisualizarGarcom();
}