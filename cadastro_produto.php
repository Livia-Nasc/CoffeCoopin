<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/cadastro.css">
</head>
<body>
        <div id="-logo-container">
            <img src="img/logo.png" alt="">
        </div>
   
        <div id="container">
            
            <div id="box">
                <div class="cadastro">
                    <form action="php/usuario.php" method="post">
                        <h1>CADASTRAR PRODUTO</h1>
                        <br><br>
                        <label for="nome">  
                            <input type="text" id="nome" required name = "nome" placeholder="Insira seu nome">
                        </label>
                        <br><br>
                        <label for="cpf"> 
                            <input  maxlength ="11" type="text" id="cpf" required name = "cpf" placeholder="Insira seu CPF"> <!-- oninput="mascara(this)" -->
                        </label>
                        <br><br>
                        <label for="email"> 
                            <input type="email" id="email" required name = "email" placeholder="Insira seu e-mail">
                        </label>
                        <br><br>
                        <label for="telefone"> 
                            <input type="tel" id="telefone" required name = "telefone" placeholder="Insira seu telefone">
                        </label>
                        <br><br>
                        <label for="data_nasc"> 
                            <input type="date" id="data_nasc" required name = "data_nasc" placeholder="Insira uma senha">
                        </label>
                        <br><br>
                        <label for="senha"> 
                            <input type="password" id="senha" required name = "senha" placeholder="Insira uma senha">
                        </label>
                        <br><br>
                        <div id="btn">
                            <button type="submit" name = "cadastrar" class="btn btn-warning">ENVIAR</button>
                        </div>
                    </form>
                </div>

            </div>

        </div>

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