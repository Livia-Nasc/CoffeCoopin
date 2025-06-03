<?php
require_once('../php/conexao.php');
session_start();

$tiposAcesso = [1];
$tipoUsuario = $_SESSION['usuario']['tipo'];
if (!in_array($tipoUsuario, $tiposAcesso)) {
    header('location:../login.php');
    exit();
}
switch ($tipoUsuario) {
    case 1:
        $arquivo = '../dashboard/admin.php';
        break;
}

$conn = getConexao();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Gerentes</title>
    <link rel="stylesheet" href="../css/cadastro_produto.css">
    <style>
        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .actions a, .actions button {
            padding: 5px 10px;
            font-size: 14px;
        }
        
        .produtos-conta {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }
        
        .produto-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .total-conta {
            font-weight: bold;
            font-size: 1.1em;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #ddd;
            color: var(--cor-principal);
        }
        
        @media (max-width: 768px) {
            .actions {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="../img/logo.png" alt="Logo">
    </div>
    <a href="<?php echo $arquivo?>" class="btn-voltar">Voltar</a>
    
    <div id="produtos">
        <h2>Lista de Gerentes</h2>
        
        <div style="margin-bottom: 20px;">
            <a href="../cadastro/gerente.php" class="btn btn-primary">Cadastrar Novo Gerente</a>
        </div>

        <form action="../php/gerente.php" method="post">
            <label for="nome">Pesquisar gerente</label> 
            <input type="text" name="nome" placeholder="Insira o nome do gerente" id="nome">
            <button type="submit" name="visualizar" id="visualizar">Visualizar gerente</button>
        </form>
        
        <?php if(isset($_SESSION['mensagem'])) { ?>
            <div class="mensagem"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
        <?php } ?>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Data Nascimento</th>
                    <th>RG</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($_SESSION['gerente']) && !empty($_SESSION['gerente'])) { ?>
                    <?php foreach($_SESSION['gerente'] as $gerente){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($gerente['nome']); ?></td>
                            <td class="cpf"><?php echo htmlspecialchars($gerente['cpf']); ?></td>
                            <td><?php echo htmlspecialchars($gerente['email']); ?></td>
                            <td><?php echo htmlspecialchars($gerente['telefone']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($gerente['data_nasc'])); ?></td>
                            <td><?php echo htmlspecialchars($gerente['rg']); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Nenhum gerente cadastrado</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            $(".cpf").mask("000.000.000-00");
        });
    </script>
</body>
</html>