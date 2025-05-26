<?php
    session_start();
    if ($_SESSION['usuario']['tipo'] != 2) {
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
    <title>Painel do Gerente</title>
    <link rel="stylesheet" href="../css/conta.css">
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
        <img src="../img/logo.png" alt="Logo" class="logo-img">
    </div>

    <div id="container">
        <div id="box">
            <div class="cadastro">
                <div class="form-container">
                    <h2>Painel do Gerente</h2>
                    <p style="text-align: center; margin-bottom: 20px;">Olá <span id="colaborador"><?php echo htmlspecialchars($nome) ?></span>, seja bem-vindo(a)!</p>
                    
                    <div class="dashboard-menu">
                        <a href="../cadastro/garcom.php" class="btn btn-primary">Novo Garçom</a>
                        <a href="../visualização/garcons.php" class="btn btn-primary">Ver Garçom</a>
                        <a href="../cadastro/produto.php" class="btn btn-primary">Novo Produto</a>
                        <a href="../visualização/produtos.php" class="btn btn-primary">Ver Produto</a>
                        <a href="../calcular_comissao.php" class="btn btn-primary" <?php unset($_SESSION['comissao']);?>>Calcular Comissão</a>
                        <a href="../gerar_relatorio.php" class="btn btn-primary">Gerar Relatório</a>
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