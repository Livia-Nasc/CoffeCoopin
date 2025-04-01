<?php
session_start();
if(!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 1) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Bem-vindo, <?= $_SESSION['usuario']['nome'] ?></h2>
    <p>Você está logado como Administrador</p>
    
    <div class="row">
        <div class="col-md-4">
            <h3>Cadastrar Gerente</h3>
            <a href="cadastro_gerente.php" class="btn btn-primary">Cadastrar</a>
        </div>
        
        <div class="col-md-4">
            <h3>Gerenciar Usuários</h3>
            <a href="lista_usuarios.php" class="btn btn-info">Listar</a>
        </div>
        
        <div class="col-md-4">
            <h3>Relatórios</h3>
            <a href="relatorios.php" class="btn btn-success">Ver</a>
        </div>
    </div>
    
    <a href="logout.php" class="btn btn-danger mt-3">Sair</a>
</div>

</body>
</html>