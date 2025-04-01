<?php
require_once('conexao.php');

//  * Cadastra o usuário
function CadastrarUsuario() {
    $conn = getConexao();

    // Get form data
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $data_nasc = $_POST['data_nasc'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $cpf = $_POST['cpf'];

    // ! vê se o usuário já existe
    $sql = 'SELECT * FROM usuario WHERE cpf = :cpf OR email = :email';// ! Selecionando o CPF e o email do usuário cadastrado
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();

    if($stmt->rowCount() == 0) { // ! Verifica se o CPF e o e-mail não estão registrados
        // ! Codifica o password
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // ! Cria um novo usuário
        $cadastro = 'INSERT INTO usuario(nome, telefone, data_nasc, email, senha, cpf)
                     VALUES(:nome, :telefone, :data_nasc, :email, :senha, :cpf)';
        $stmt = $conn->prepare($cadastro);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':senha', $senhaHash); // Store hashed password
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cpf', $cpf);

        if ($stmt->execute()) {
            header('Location: ../login.php');
            exit();
        } else {
            header('Location: ../cadastro.php');
            exit();
        }
    } else {
        header('Location: ../cadastro.php');
        exit();
    }
}

// * Login usuário
function LoginUsuario() {
    $conn = getConexao();

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = 'SELECT * FROM usuario WHERE email = :email';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if($stmt->rowCount() == 1) {
        $dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // ! Verifica se a senha passada é igual a da senha decodificada que foi cadastrada
        if(password_verify($senha, $dadosUsuario['senha'])) {
            // ! inicia a sessão
            session_start();
            $_SESSION['usuario'] = [
                'id' => $dadosUsuario['id'],
                'nome' => $dadosUsuario['nome'],
                'email' => $dadosUsuario['email'],
                'tipo' => $dadosUsuario['tipo']
            ];

            // ! redireciona baseado no tipo de usuário
            switch($dadosUsuario['tipo']) {
                case 'admin':
                    header('Location: ../admin/dashboard.php');
                    break;
                case 'gerente':
                    header('Location: ../gerente/dashboard.php');
                    break;
                case 'garcom':
                    header('Location: ../garcom/dashboard.php');
                    break;
                default:
                    header('Location: ../home.html');
            }
            exit();
        } else {
            header('Location: ../login.php?error=password');
            exit();
        }
    } else {
        header('Location: ../login.php?error=notfound');
        exit();
    }
}

if(isset($_POST['cadastrar'])) {
    CadastrarUsuario();
}

if(isset($_POST['login'])) {
    LoginUsuario();
}