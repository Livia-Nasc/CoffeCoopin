<?php
require_once('../php/conexao.php');
session_start();

// Verifica se o usuário está logado e é admin (tipo 1)
if ($_SESSION['usuario']['tipo'] != 1) {
    header('location:../login.php');
    exit();
}

$conn = getConexao();

// Busca todos os gerentes
$sql = "SELECT g.id, u.nome, u.cpf, u.email, u.telefone, u.data_nasc, g.rg 
        FROM usuario u
        JOIN gerente g ON u.id = g.user_id
        WHERE u.tipo = 2"; // Tipo 2 = Gerente

$stmt = $conn->prepare($sql);
$stmt->execute();
$gerentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Definir filtro padrão (se necessário)
$filtro_ativo = isset($_GET['status']) ? $_GET['status'] : 'todos';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Gerentes</title>
    <link rel="stylesheet" href="../css/conta.css">
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
    <a href="../dashboard/admin.php" class="btn-voltar">Voltar</a>
    
    <div id="produtos">
        <h2>Lista de Gerentes</h2>
        <div style="margin-bottom: 20px;">
            <a href="../cadastro/gerente.php" class="btn btn-primary">Cadastrar Novo Gerente</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Data Nasc.</th>
                    <th>RG</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($gerentes)){ ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Nenhum gerente cadastrado</td>
                    </tr>
                <?php }else{ ?>
                    <?php foreach ($gerentes as $gerente){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($gerente['nome']); ?></td>
                            <td><?php echo htmlspecialchars($gerente['cpf']); ?></td>
                            <td><?php echo htmlspecialchars($gerente['email']); ?></td>
                            <td><?php echo htmlspecialchars($gerente['telefone']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($gerente['data_nasc'])); ?></td>
                            <td><?php echo htmlspecialchars($gerente['rg']); ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php if(isset($_SESSION['mensagem'])): ?>
        <div class="mensagem-alerta">
            <?php 
                echo $_SESSION['mensagem'];
                unset($_SESSION['mensagem']); 
            ?>
        </div>
    <?php endif; ?>

    <script>
        // Confirmação antes de excluir
        document.querySelectorAll('.btn-warning').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Tem certeza que deseja excluir este gerente?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>