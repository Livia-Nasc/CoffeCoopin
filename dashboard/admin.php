<?php
session_start();
if ($_SESSION['usuario']['tipo'] != 1) {
    header('location:../index.php');
    exit();
}
$nome = $_SESSION['usuario']['nome'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="../css/conta.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <div class="-logo-container">
        <img src="../img/logo.png" alt="Logo" class="logo-img">
    </div>

    <div id="container">
        <div id="box">
            <div class="cadastro">
                <div class="form-container">
                    <h2>Painel Administrativo</h2>
                    <p style="text-align: center; margin-bottom: 20px;">Olá <span id="colaborador"><?php echo htmlspecialchars($nome) ?></span>, seja bem-vindo(a)!</p>
                    
                    <div class="dashboard-menu">
                        <a href="../abrir_conta.php" class="btn btn-primary">Abrir Conta</a>
                        <a href="../visualização/conta.php" class="btn btn-primary">Ver Contas</a>
                        <a href="../cadastro/produto.php" class="btn btn-primary">Cadastrar Produto</a>
                        <a href="../visualização/produtos.php" class="btn btn-primary">Ver Produtos</a>
                        <a href="../cadastro/gerente.php" class="btn btn-primary">Cadastrar Gerente</a>
                        <a href="../visualização/gerentes.php" class="btn btn-primary">Ver Gerentes</a>
                        <a href="../cadastro/garcom.php" class="btn btn-primary">Cadastrar Garçom</a>
                        <a href="../visualização/garcons.php" class="btn btn-primary">Ver Garçons</a>
                        <a href="../comissao/calcular.php" class="btn btn-primary" <?php unset($_SESSION['comissao']);?>>Calcular Comissão</a>
                        <a href="../relatorio_mesas.php" class="btn btn-primary" <?php unset($_SESSION['relatorio_mesas']);?>>Relatório de mesas</a>
                        <a href="../comissao/historico.php" class="btn btn-primary">Histórico de Comissões</a>
                    </div>
                    
                    <form action="../php/usuario.php" method="post" style="margin-top: 30px; text-align: center;">
                        <button type="submit" name="sair" class="btn btn-warning">Sair</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if(isset($_SESSION['mensagem'])) { ?>
        <div class="mensagem-alerta">
            <?php 
                echo $_SESSION['mensagem'];
                unset($_SESSION['mensagem']); 
            ?>
        </div>
    <?php } ?>
</body>
</html>