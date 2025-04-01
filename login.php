<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/cadastro.css">
</head>
<body>
        <div id="-logo-container">
            <img src="img/logo.png" alt="Logo da Cafeteria">
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
                            <input type="password" id="senha" required name="senha" placeholder="Insira sua senha">
                        </label>
                        <br><br>
                        <div id="btn">
                            <button type="submit" name="login" class="btn btn-warning">LOGAR</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>