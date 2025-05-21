<?php
session_start();
$comissao = $_SESSION['comissao'] ?? 'Nenhum cálculo realizado ainda';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cálculo de Comissão</title>
    <link rel="stylesheet" href="../css/conta.css">
</head>
<body>
    <div class="logo-container">
        <img src="../img/group 1.png" alt="Logo" class="logo-img">
    </div>

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