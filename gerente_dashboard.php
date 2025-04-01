<?php
session_start();
if(!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 2) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Bem-vindo, <?= $_SESSION['usuario']['nome'] ?></h2>
    <p>Você está logado como Gerente</p>
    
    <div class="row">
        <div class="col-md-6">
            <h3>Cadastrar Produto</h3>
            <a href="cadastro_produto.php" class="btn btn-primary">Ir para Cadastro</a>
        </div>
        
        <div class="col-md-6">
            <h3>Relatórios</h3>
            <form method="post" action="relatorios.php">
                <button type="submit" name="relatorio_vendas" class="btn btn-info">Vendas</button>
                <button type="submit" name="relatorio_comissoes" class="btn btn-info">Comissões</button>
            </form>
        </div>
    </div>
    
    <a href="logout.php" class="btn btn-danger mt-3">Sair</a>
</div>

</body>
</html>