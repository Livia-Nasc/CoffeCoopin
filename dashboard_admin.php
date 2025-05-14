<?php
session_start();
if ($_SESSION['usuario']['tipo'] != 1) {
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
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="css/conta.css">
    <style>
        body{
            overflow: hidden;
        }
    </style>
</head>

<body>
    <div class="-logo-container">
        <img src="img/logo.png" alt="Logo" class="logo-img">
    </div>

    <div id="container">
        <div id="box">
            <div class="cadastro">
                <div class="form-container">
                    <h2>Painel Administrativo</h2>
                    <p style="text-align: center; margin-bottom: 20px;">Olá <span id="colaborador"><?php echo htmlspecialchars($nome) ?></span>, seja bem-vindo(a)!</p>
                    
                    <div class="dashboard-menu">
                        <a href="abrir_conta.php" class="btn btn-primary">Abrir Conta</a>
                        <a href="visualizar_produto.php" class="btn btn-primary">Visualizar Produtos</a>
                        <a href="cadastro_gerente.php" class="btn btn-primary">Cadastrar Gerente</a>
                        <a href="ver_conta.php" class="btn btn-primary">Ver Contas</a>
                    </div>
                    
                    <form action="php/usuario.php" method="post" style="margin-top: 30px; text-align: center;">
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

    <style>
        .dashboard-menu {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .dashboard-menu a {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            text-align: center;
            height: 100%;
        }
        
        @media (max-width: 768px) {
            .dashboard-menu {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>