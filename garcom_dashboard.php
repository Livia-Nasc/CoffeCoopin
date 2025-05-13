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
    <title>Painel garçom</title>
    <link rel="stylesheet" href="css/conta.css">
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
</head>

<body>
    <div class="-logo-container">
        <img src="img/group 1.png" alt="Logo" class="logo-img">
    </div>

    <div id="container">
        <div id="box">
            <div class="cadastro">
                <div class="form-container">
                    <h2>Painel garçom</h2>
                    <p style="text-align: center; margin-bottom: 20px;">Olá <span id="colaborador"><?php echo htmlspecialchars($nome) ?></span>, seja bem-vindo(a)!</p>
                    
                    <div class="dashboard-menu">
                        <a href="abrir_conta.php" class="btn btn-primary">Abrir conta</a>
                        <a href="visualizar_produto.php" class="btn btn-primary">Visualizar produto</a>
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

    
</body>
</html>