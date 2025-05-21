<?php
session_start();
$comissao = $_SESSION['comissao'] ?? 'Nenhum cálculo realizado ainda';

$tiposAcesso = [1,2];
$tipoUsuario = $_SESSION['usuario']['tipo'];
if (!in_array($tipoUsuario, $tiposAcesso)) {
    header('location:login.php');
    exit();
}
switch ($tipoUsuario) {
    case 1:
        $arquivo = 'dashboard_admin.php';
        break;
    case 2:
        $arquivo = 'dashboard_gerente.php';
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cálculo de Comissão</title>
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
    <a href="<?php echo $arquivo?>" class="btn-voltar">Voltar</a>

    <form action="php/produto.php" method="post">
        <label for="nome">Pesquisar garçom</label> 
        <input type="text" name="nome" placeholder="Insira o nome do produto" id="nome">
        <button type="submit" name="visualizar" id="visualizar">Visualizar garçom</button>
    </form>
    <div id="container">
        <div id="box">
            <div class="cadastro">
                <div class="form-container">
                    <h2>Cálculo de Comissão</h2>
                    
                    <form action="php/gerente.php" method="post">
                        <button type="submit" name="calcular_comissao" class="btn btn-primary">Calcular Comissão</button>
                    </form>
                    
                    <div class="resultado-comissao">
                        <h3>Resultado:</h3>
                        <p><?php echo $comissao; ?></p>
                    </div>
                    
                    <a href="dashboard_gerente.php" class="btn btn-warning">Voltar</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>