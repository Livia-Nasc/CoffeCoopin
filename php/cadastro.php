<?php
    require_once('conexao.php');

    $conn = getConexao();

    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $data_nasc = $_POST['data_nasc'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $cpf = $_POST['cpf'];

    $sql = 'INSERT INTO usuario(nome, telefone, data_nasc, email, senha, cpf) VALUES( :nome, :telefone, :data_nasc,:email, :senha, :cpf)';
    $smtm = $conn -> prepare($sql);
    $smtm -> bindParam(':nome', $nome);
    $smtm -> bindParam(':telefone', $telefone);
    $smtm -> bindParam(':data_nasc', $data_nasc);
    $smtm -> bindParam(':email', $email);
    $smtm -> bindParam(':senha', $senha);
    $smtm -> bindParam(':cpf', $cpf);
    if ($smtm->execute())
    {
        echo "deu certo";
        header('location: ../login.html');
    }
    else
    {
        echo "não deu certo";
    }

?>