<?php
require_once('php/conexao.php');
session_start();

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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Gerentes</title>
    <link rel="stylesheet" href="css/cadastro_produto.css">
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
    <div class="-logo-container">
        <img src="img/logo.png" alt="Logo">
    </div>
    <a href="<?php echo $arquivo?>" class="btn-voltar">Voltar</a>
    
    <div id="produtos">
        <h2>Lista de Garçons</h2>
        <div style="margin-bottom: 20px;">
            <a href="cadastro_garcom.php" class="btn btn-primary">Cadastrar novo garçom</a>
        </div>
        <form action="php/garcom.php" method="post">
            <label for="nome">Pesquisar garçom</label> 
            <input type="text" name="nome" placeholder="Insira o nome do garçom" id="nome">
            <button type="submit" name="visualizar" id="visualizar">Visualizar garcom</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Data Nascimento</th>
                    <th>Escolaridade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($_SESSION['garcom']) && !empty($_SESSION['garcom'])) { ?>
                    <?php foreach($_SESSION['garcom'] as $garcom){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($garcom['nome']); ?></td>
                            <td class="cpf"><?php echo htmlspecialchars($garcom['cpf']); ?></td>
                            <td><?php echo htmlspecialchars($garcom['email']); ?></td>
                            <td><?php echo htmlspecialchars($garcom['telefone']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($garcom['data_nasc'])); ?></td>
                            <td><?php echo htmlspecialchars($garcom['escolaridade']); ?></td>
                            <td class="actions">
                                <a href="editar_gerente.php?id=<?php echo $garcom['id']; ?>" class="btn btn-primary">Editar</a>
                                <form method="post" action="php/garcom.php" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $garcom['id']; ?>">
                                    <button type="submit" name="excluir_gerente" class="btn btn-warning" 
                                            onclick="return confirm('Tem certeza que deseja excluir este garçom?')">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <!-- Linha adicional para informações extras se necessário -->
                        <tr>
                            <td colspan="7" style="padding: 0;">
                                <div class="produtos-conta">
                                    <strong>Informações Adicionais:</strong>
                                    <div class="produto-item">
                                        <span>Último Acesso:</span>
                                        <span>12/05/2023</span> <!-- Substituir por dados reais -->
                                    </div>
                                    <div class="produto-item">
                                        <span>Contas Gerenciadas:</span>
                                        <span>15</span> <!-- Substituir por dados reais -->
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Nenhum garçom cadastrado</td>
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