<?php
require_once('php/conexao.php');
session_start();

// Verifica se o usuário está logado e é admin (tipo 1)
if ($_SESSION['usuario']['tipo'] != 1) {
    header('location:login.php');
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
    <link rel="stylesheet" href="css/conta.css">
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
        <img src="img/logo.png" alt="Logo">
    </div>
    <a href="admin_dashboard.php" class="btn-voltar">Voltar</a>

    <div class="filtro-container">
        <h3>Filtrar Gerentes</h3>
        <div>
            <a href="?status=todos" class="filtro-btn <?php echo $filtro_ativo == 'todos' ? 'active' : ''; ?>">Todos</a>
            <a href="?status=ativo" class="filtro-btn <?php echo $filtro_ativo == 'ativo' ? 'active' : ''; ?>">Ativos</a>
            <a href="?status=inativo" class="filtro-btn <?php echo $filtro_ativo == 'inativo' ? 'active' : ''; ?>">Inativos</a>
        </div>
    </div>
    
    <div id="produtos">
        <h2>Lista de Gerentes</h2>
        <div style="margin-bottom: 20px;">
            <a href="cadastro_gerente.php" class="btn btn-primary">Cadastrar Novo Gerente</a>
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
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($gerentes)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Nenhum gerente cadastrado</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($gerentes as $gerente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($gerente['nome']); ?></td>
                            <td><?php echo htmlspecialchars($gerente['cpf']); ?></td>
                            <td><?php echo htmlspecialchars($gerente['email']); ?></td>
                            <td><?php echo htmlspecialchars($gerente['telefone']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($gerente['data_nasc'])); ?></td>
                            <td><?php echo htmlspecialchars($gerente['rg']); ?></td>
                            <td class="actions">
                                <a href="editar_gerente.php?id=<?php echo $gerente['id']; ?>" class="btn btn-primary">Editar</a>
                                <form method="post" action="php/gerente.php" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $gerente['id']; ?>">
                                    <button type="submit" name="excluir_gerente" class="btn btn-warning" 
                                            onclick="return confirm('Tem certeza que deseja excluir este gerente?')">
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
                    <?php endforeach; ?>
                <?php endif; ?>
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