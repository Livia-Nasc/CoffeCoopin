<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="css/cadastro_produto.css">
</head>

<body>
    <div id="container">
        <div id="box">
            <div class="cadastro">
                <form action="php/produto.php" method="post" class="form-container">
                    <h2>CADASTRAR PRODUTO</h2>
                    <br><br>
                    <label for="nome">
                        <input type="text" id="nome" required name="nome" placeholder="Insira o nome do produto">
                    </label>
                    <br><br>
                    <label for="preco">
                        <input type="text" id="preco" required name="preco" placeholder="Insira o preço" oninput="mascara(this)">
                    </label>
                    <br><br>
                    <select name="categoria" id="categoria" required>
                        <option value="">Escolha</option>
                        <option value="comida">Comida</option>
                        <option value="bebida">Bebida</option>
                    </select>
                    <br><br>
                    <select name="porcao" id="porcao" required>
                        <option value="">Escolha</option>
                        <option value="grande">Grande</option>
                        <option value="media">Média</option>
                        <option value="pequena">Pequena</option>
                    </select>
                    <br><br>
                    <br><br>
                    <label for="qtd_estoque">
                        <input type="text" id="qtd_estoque" required name="qtd_estoque" placeholder="Insira o estoque">
                    </label>
                    <br><br>
                    <div id="btn">
                        <button type="submit" name="cadastrar" class="btn btn-warning">ENVIAR</button>
                        <a href="visualizar_produto.php" class="btn btn-primary">Visualizar Produtos</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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