<?php
    require_once('conexao.php');

    function AbrirConta(){
        $conn = getConexao();
        $mesa = $_POST['mesa'];
        $garcom_id = $_POST['garcom_id'];

        $sql = "INSERT INTO conta (mesa, garcom_id, data_abertura, status)
            VALUES (:mesa, :garcom_id, NOW(), 'aberta')";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':mesa', $mesa);
        $stmt -> bindParam(':garcom_id', $garcom_id);
        if($stmt -> execute()){
            echo "<script type='text/javascript'>
                        alert('Conta aberta');  // ! Se o CPF e o e-mail já existirem, exibe mensagem de erro na página cadastro.php
                        window.location='../abrir_conta.php';
                      </script>";
        }
        else{
            echo "<script type='text/javascript'>
                        alert('Informações inexistentes');  // ! Se o CPF e o e-mail já existirem, exibe mensagem de erro na página cadastro.php
                        window.location='../abrir_conta.php';
                      </script>";
        }
    }

    function VerConta(){
        session_start();
        $conn = getConexao();
        $sql = "SELECT mesa, garcom_id, data_abertura, status FROM conta";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $_SESSION['conta'] = $stmt->fetchAll();
            header("Location: ../abrir_conta.php");
    }
    if(isset($_POST['abrir_conta'])){
        AbrirConta();
    }
    if(isset($_POST['visualizar'])){
        VerConta();
    }
?>