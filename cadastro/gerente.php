<?php
session_start();
if ($_SESSION['usuario']['tipo'] != 1) {
    header('location:../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Gerente</title>
    <link rel="stylesheet" href="../css/conta.css">
</head>

<body>
    <div class="-logo-container">
        <img src="../img/logo.png" alt="Logo" class="logo-img">
    </div>
    <a href="../dashboard/admin.php" class="btn-voltar">Voltar</a>

    <div id="container">
        <div id="box">
            <div class="cadastro">
                <form action="../php/gerente.php" method="post" class="form-container">
                    <h2>CADASTRAR GERENTE</h2>
                    <br>
                    
                    <label for="nome">Nome completo</label>
                        <input type="text" id="nome" required name="nome" placeholder="Insira o nome completo">
                    <br>
                    
                    <label for="cpf">CPF</label>
                        <input oninput="mascara(this)" type="text" id="cpf" required name="cpf" placeholder="Insira o CPF">
                    <br>
                    
                    <label for="email">E-mail</label>
                        <input type="email" id="email" required name="email" placeholder="Insira o e-mail">
                    <br>
                    
                    <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" required name="telefone" placeholder="Insira o telefone">
                    <br>
                    
                    <label for="data_nasc">Data de nascimento</label>
                        <input type="date" id="data_nasc" required name="data_nasc">
                    <br>
                    
                    <label for="rg">RG</label>
                        <input type="text" id="rg" required name="rg" placeholder="Insira o RG">
                    <br>
                    
                    <label for="senha">Senha</label>
                        <input type="password" id="senha" required name="senha" placeholder="Insira uma senha">
                    <br>
                    
                    <div id="btn">
                        <button type="submit" name="cadastrar_gerente" class="btn btn-primary" onclick="return confirm('Os dados estão corretos?')">ENVIAR</button>
                        <a href="../visualização/gerentes.php"><button type="button" class="btn btn-primary">Visualizar Gerentes</button></a>
                    </div>
                </form>
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

    <script>
        function mascara(i) {
            var v = i.value;

            if (isNaN(v[v.length - 1])) {
                i.value = v.substring(0, v.length - 1);
                return;
            }

            i.setAttribute("maxlength", "14");
            if (v.length == 3 || v.length == 7) i.value += ".";
            if (v.length == 11) i.value += "-";
        }
    </script>
</body>
</html>