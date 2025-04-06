<?php
    require_once("conexao.php");
    require("usuario.php");

    function CadastrarGerente(){
            $conn = getConexao();

            $nome = $_POST['nome'];
            $telefone = $_POST['telefone'];
            $data_nasc = $_POST['data_nasc'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $cpf = $_POST['cpf'];
            $rg = $_POST['rg'];

            $sql = 'SELECT * FROM usuario WHERE cpf = :cpf OR email = :email'; // ! Seleciona dados usando o CPF e o email do usuário a ser cadastrado
            $stmt = $conn -> prepare($sql);
            $stmt -> bindParam(':email', $email);
            $stmt -> bindParam(':cpf', $cpf);
            $stmt->execute();

            if($stmt -> rowcount() == 0 ){ // ! Verifica se o CPF e o e-mail não estão registrados
                
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $cadastro = 'INSERT INTO usuario(nome, telefone, data_nasc, email, senha, cpf, tipo) VALUES( :nome, :telefone, :data_nasc,:email, :senha, :cpf, 2)'; // ! Insere dados passados no formulário contido na página cadastro.php na tabela usuario
                $stmt = $conn -> prepare($cadastro);
                $stmt -> bindParam(':nome', $nome);
                $stmt -> bindParam(':telefone', $telefone);
                $stmt -> bindParam(':data_nasc', $data_nasc);
                $stmt->bindParam(':senha', $senhaHash);
                $stmt -> bindParam(':email', $email);
                $stmt -> bindParam(':cpf', $cpf);

                if ($stmt->execute()){
                    $user_id = $conn->lastInsertId();

                    // ! Insere os dados na tabela gerente
                    $cadastroGerente = 'INSERT INTO gerente(rg, user_id) VALUES(:rg, :user_id)';
                    $stmt = $conn->prepare($cadastroGerente);
                    $stmt->bindParam(':rg', $rg);
                    $stmt->bindParam(':user_id', $user_id);
                    if ($stmt->execute()){
                        header('location: ../login.php'); // ! Vai para a página de login
                        exit();
                    }
                    else{
                        echo "Erro ao cadastrar usuário.";
                    }
                }
            }
            else
            {
                echo "<script type='text/javascript'>
                        alert('Informações já existentes');  // ! Se o CPF e o e-mail já existirem, exibe mensagem de erro na página cadastro.php
                        window.location='../cadastro.php';
                      </script>";
            }
        }
        if(isset($_POST['cadastrar_gerente'])){
            CadastrarGerente();
        }
?>