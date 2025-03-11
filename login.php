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
                    <h1 id="titulo">LOGIN</h1>
                    <form action="php/usuario.php" method="post">
                        <br><br>
                        <label for="email"> 
                            <input type="email" id="email" required name="email" placeholder="Insira seu e-mail">
                        </label>
                        <br><br>
                        <label for="senha"> 
                            <input type="password" id="senha" required name="senha" placeholder="Insira uma senha">
                        </label>
                        <br><br>
                        <div id="btn">
                            <button type="submit" name="login" class="btn btn-warning">LOGAR</button>
                        </div>
                    </form>
                </div>

            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>