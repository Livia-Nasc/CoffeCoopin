<?php
session_start();
if(!isset($_SESSION['usuario'])|| $_SESSION['usuario']['tipo'] != 3) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Garçom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Bem-vindo, <?= $_SESSION['usuario']['nome'] ?></h2>
    <p>Você está logado como Garçom</p>
    
    <h3>Abrir Conta</h3>
    <form method="post" action="conta.php">
        <input type="number" name="mesa" placeholder="Número da mesa" required>
        <button type="submit" name="abrir" class="btn btn-primary">Abrir</button>
    </form>
    
    <h3>Contas Abertas</h3>
    <!-- Aqui viria a lista de contas abertas -->
    
    <a href="logout.php" class="btn btn-danger">Sair</a>
</div>

</body>
</html>