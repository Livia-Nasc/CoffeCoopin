<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/cadastro.css">
    <style>
table {
    text-align:center;
  margin-top: 10px;
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
th{
    font-weight: 600;
}

td, th {
  border: 1px solid #000;
  text-align: left;
  padding: 8px;
}
#visualizar{
  background-color:rgb(241, 197, 152)
}
</style>
</head>
<body>
        <div id="-logo-container">
            <img src="img/logo.png" alt="">
        </div>
   
        <div id="container">
            
            <div id="box">
                <div class="cadastro">
                    <form action="php/produto.php" method="post">
                        <h1>CADASTRAR PRODUTO</h1>
                        <br><br>
                        <label for="nome">  
                            <input type="text" id="nome" required name = "nome" placeholder="Insira o nome do produto">
                        </label>
                        <br><br>
                        <label for="preco"> 
                            <input type="text" id="preco" required name = "preco" placeholder="Insira o preço">
                        </label>
                        <br><br>
                        <select name="categoria" id="categoria" name="categoria" >
                            <option value="">Escolha</option>
                            <option value="comida">Comida</option>
                            <option value="bebida">Bebida</option>
                        </select>
                        <br><br>
                        <select name="porcao" id="porcao" name="porcao" >
                            <option value="">Escolha</option>
                            <option value="grande">Grande</option>
                            <option value="media">Média</option>
                            <option value="pequena">Pequena</option>
                        </select>
                        <br><br>
                        <br><br>
                        <label for="qtd_estoque"> 
                            <input type="text" id="qtd_estoque" required name = "qtd_estoque" placeholder="Insira o estoque">
                        </label>
                        <br><br>
                        <div id="btn">
                            <button type="submit" name = "cadastrar" class="btn btn-warning">ENVIAR</button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
        <form action="php/produto.php" method="post">
            <button type="submit" name = "visualizar" id="visualizar">Visualizar produtos</button>
        </form>
        <table>
        <tr>
            <th>Nome</th>
            <th>Porção</th>
            <th>Qtd Estoque</th>
            <th>Categoria</th>
            <th>Preço</th>
        </tr>
        <?php
            $nome = $_SESSION['nome'];
            $porcao = $_SESSION['porcao'];
            $qtd_estoque = $_SESSION['qtd_estoque'];
            $categoria = $_SESSION['categoria'];
            $preco = $_SESSION['preco'];
            for ($i = 0; $i < count($nome); $i++) {
        ?>
        
        <tr>
            <td><?php echo $nome[$i]; ?></td>
            <td><?php echo $porcao[$i]; ?></td>
            <td><?php echo $qtd_estoque[$i]; ?></td>
            <td><?php echo $categoria[$i]; ?></td>
            <td><?php echo $preco[$i]; ?></td>
        </tr>
        
                <!-- <p>Nome: <?php echo $nome[$i]; ?></p>
                <p>Porção: <?php echo $porcao[$i]; ?></p>
                <p>Categoria: <?php echo $categoria[$i]; ?></p>
                <p>Preço: <?php echo $preco[$i]; ?></p>
                <p>Qtd estoque: <?php echo $qtd_estoque[$i]; ?></p> -->
        <?php
            }
        ?>
        </table>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        <script>
            function mascara(i){
   
   var v = i.value;
   
   if(isNaN(v[v.length-1])){
      i.value = v.substring(0, v.length-1);
      return;
   }
   
   i.setAttribute("maxlength", "14");
   if (v.length == 3 || v.length == 7) i.value += ".";
   if (v.length == 11) i.value += "-";

}
        </script>
</body>
</html>