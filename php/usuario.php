<?php
    require_once('conexao.php');

    //  * Cadastra o usuário

    function CadastrarUsuario(){
        $conn = getConexao();

        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $data_nasc = $_POST['data_nasc'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $cpf = $_POST['cpf'];

        $sql = 'SELECT * FROM usuario WHERE cpf = :cpf OR email = :email'; // ! Selecionando o CPF e o email do usuário cadastrado
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':email', $email);
        $stmt -> bindParam(':cpf', $cpf);
        $stmt->execute();

        if($stmt -> rowcount() == 0 ){ // ! Verifica se o CPF e o e-mail não estão registrados
            $cadastro = 'INSERT INTO usuario(nome, telefone, data_nasc, email, senha, cpf) VALUES( :nome, :telefone, :data_nasc,:email, :senha, :cpf)'; // ! Inserindo dados passados no formulário contido na página cadastro.php na tabela usuario 
            $stmt = $conn -> prepare($cadastro);
            $stmt -> bindParam(':nome', $nome);
            $stmt -> bindParam(':telefone', $telefone);
            $stmt -> bindParam(':data_nasc', $data_nasc);
            $stmt -> bindParam(':senha', $senha);
            $stmt -> bindParam(':email', $email);
            $stmt -> bindParam(':cpf', $cpf); 
            
            if ($stmt->execute()){
                header('location: ../login.php'); // ! Vai para a página de login
                exit();
            }
            else{
                echo "Erro ao cadastrar usuário.";
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

    // * Chama a função CadastrarUsuario se o botão de cadastro for clicado
    if(isset($_POST['cadastrar'])){
        CadastrarUsuario();
    }

    // * Login usuário

    // ? Fazer a parte um 'switch case' pra tipo de usuario q está entrando (proprietário, gerente, garçom, cliente)
    function LoginUsuario(){
        $conn = getConexao();

        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $sql = 'SELECT * FROM usuario WHERE email = :email';
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':email', $email);
        $stmt->execute();

        if($stmt -> rowcount() == 1 ){
            $dadosUsuario = $stmt -> fetch();
            $senhaBanco = $dadosUsuario['senha'];
            if($senha == $senhaBanco){
                header('location: ../home.html');  // ! Vai para a página de login
                exit();
            }
            else{
                /*Envia um alert para a página de login*/
                echo "<script type='text/javascript'>
                        alert('Senha incorreta');
                        window.location='../login.php';
                      </script>";
            }
        }
        else{
            echo "<script type='text/javascript'>
                    alert('Dados incorretos');
                    window.location='../login.php';
                  </script>";
        }
    }

    if(isset($_POST['login'])){
        LoginUsuario();
    }
?>
