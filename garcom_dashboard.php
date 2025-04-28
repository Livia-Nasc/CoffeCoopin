<?php
    session_start();
    if ($_SESSION['usuario']['tipo'] != 3) {
        header('location:login.php');
        exit();
    }
    $nome = $_SESSION['usuario']['nome'];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixa</title>
</head>
<body>
    <p>Olá <span id="colaborador"><?php echo $nome ?></span>, seja bem vindo!</p>
            
    <form action="php/usuario.php" method="post">
        <button type="submit" name="sair">Sair</button>
    </form>
    <ul>
        <li><a href="cadastro_garcom.php">Novo garçom</a></li>
        <li><a href="cadastro_produto.php">Novo produto</a></li>
        <li><a href="#">Clientes</a></li>
    </ul>
</body>
</html>