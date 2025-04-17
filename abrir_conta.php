<?php
    require_once('php/conexao.php');
    session_start();
    $conn = getConexao();
    $sql = "SELECT id, nome, preco, categoria, porcao, qtd_estoque FROM produto";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $_SESSION['produto'] = $stmt->fetchAll();
    
    // Definir filtro padrão (mostrar todas as contas)
    $filtro_status = isset($_GET['status']) ? $_GET['status'] : 'todas';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Contas</title>
    <link rel="stylesheet" href="css/conta.css">
</head>
<body>
    <div class="form-container">
        <h2>Abrir Nova Conta</h2>
        <form method="post" action="php/conta.php">
            <input type="number" name="mesa" placeholder="Número da mesa" required>
            <input type="number" name="garcom_id" placeholder="ID Garçom" required>
            <button type="submit" name="abrir_conta" class="btn btn-primary">Abrir Conta</button>
        </form>
    </div>

    <div class="filtro-container">
        <h3>Filtrar Contas</h3>
        <div>
            <a href="?status=todas" class="filtro-btn <?php echo $filtro_status == 'todas' ? 'active' : ''; ?>">Todas as Contas</a>
            <a href="?status=aberta" class="filtro-btn <?php echo $filtro_status == 'aberta' ? 'active' : ''; ?>">Contas Abertas</a>
            <a href="?status=fechada" class="filtro-btn <?php echo $filtro_status == 'fechada' ? 'active' : ''; ?>">Contas Fechadas</a>
            <a href="?status=cancelada" class="filtro-btn <?php echo $filtro_status == 'cancelada' ? 'active' : ''; ?>">Contas Canceladas</a>
        </div>
    </div>

    <form action="php/conta.php" method="post">
        <button type="submit" name="visualizar" id="visualizar">Atualizar Lista de Contas</button>
    </form>

    <?php if(isset($_SESSION['produtos']) && isset($_SESSION['conta'])): ?>
    <div class="form-container">
        <h3>Associar Produto a Conta</h3>
        <form method="post" action="php/conta.php">
            <select name="conta_id" required>
                <option value="">Selecione a Conta</option>
                <?php foreach($_SESSION['conta'] as $conta): ?>
                    <?php if($conta['status'] == 'aberta'): ?>
                        <option value="<?php echo $conta['id']; ?>">
                            Mesa <?php echo $conta['mesa']; ?> (ID: <?php echo $conta['id']; ?>)
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            
            <select name="produto_id" required>
                <option value="">Selecione o Produto</option>
                <?php foreach($_SESSION['produtos'] as $produto): ?>
                    <option value="<?php echo $produto['id']; ?>">
                        <?php echo $produto['nome']; ?> - R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <input type="number" name="quantidade" placeholder="Quantidade" min="1" value="1" required>
            
            <button type="submit" name="associar_produto" class="associar-btn">Adicionar Produto</button>
        </form>
    </div>
    <?php endif; ?>
    
    <div id="produtos">
        <h2>Lista de Contas</h2>
        <table>
            <thead>
                <tr>
                    <th>Mesa</th>
                    <th>Garçom ID</th>
                    <th>Data Abertura</th>
                    <th>Status</th>
                    <th>ID</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($_SESSION['conta'])) {
                        foreach ($_SESSION['conta'] as $conta) {
                            // Aplicar filtro
                            if($filtro_status != 'todas' && $conta['status'] != $filtro_status) {
                                continue;
                            }
                            
                            $mesa = $conta['mesa'] ?? '';
                            $garcom_id = $conta['garcom_id'] ?? '';
                            $data_abertura = $conta['data_abertura'] ?? '';
                            $status = $conta['status'] ?? '';
                            $id = $conta['id'] ?? 0;
                            
                            // Buscar produtos associados a esta conta
                            $conn = getConexao();
                            $sql_produtos = "SELECT pd.id as pedido_id, p.nome, p.preco, pd.quantidade 
                                             FROM pedido pd
                                             JOIN produto p ON pd.produto_id = p.id
                                             WHERE pd.conta_id = :conta_id";
                            $stmt_produtos = $conn->prepare($sql_produtos);
                            $stmt_produtos->bindParam(':conta_id', $id);
                            $stmt_produtos->execute();
                            $produtos_conta = $stmt_produtos->fetchAll();
                ?>
                <tr>
                    <td><?php echo $mesa; ?></td>
                    <td><?php echo $garcom_id; ?></td>
                    <td><?php echo $data_abertura; ?></td>
                    <td><?php echo ucfirst($status); ?></td>
                    <td><?php echo $id; ?></td>
                    <td>
                        <?php if($status == 'aberta'): ?>
                            <form method="post" action="php/conta.php" style="display: inline;">
                                <input type="hidden" name="conta_id" value="<?php echo $id; ?>">
                                <button type="submit" name="fechar_conta" class="fechar-btn">Fechar Conta</button>
                            </form>
                            <form method="post" action="php/conta.php" style="display: inline;">
                                <input type="hidden" name="conta_id" value="<?php echo $id; ?>">
                                <button type="submit" name="cancelar_conta" class="cancelar-btn">Cancelar Conta</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="padding: 0;">
                        <div class="produtos-conta">
                            <strong>Produtos:</strong>
                            <?php if(count($produtos_conta) > 0): ?>
                                <?php 
                                    $total_conta = 0;
                                    foreach($produtos_conta as $produto): 
                                        $subtotal = $produto['preco'] * $produto['quantidade'];
                                        $total_conta += $subtotal;
                                ?>
                                    <div class="produto-item">
                                        <span>
                                            <?php echo $produto['nome']; ?> 
                                            (<?php echo $produto['quantidade']; ?> x R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>)
                                            
                                            <?php if($status == 'aberta'): ?>
                                                <form method="post" action="php/conta.php" style="display: inline;">
                                                    <input type="hidden" name="pedido_id" value="<?php echo $produto['pedido_id']; ?>">
                                                    <button type="submit" name="excluir_pedido" class="btn-excluir" 
                                                            onclick="return confirm('Tem certeza que deseja excluir este item?')">
                                                        Excluir
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </span>
                                        <span>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></span>
                                    </div>
                                <?php endforeach; ?>
                                <div class="produto-item total-conta">
                                    <span>Total da Conta:</span>
                                    <span>R$ <?php echo number_format($total_conta, 2, ',', '.'); ?></span>
                                </div>
                            <?php else: ?>
                                <div>Nenhum produto associado</div>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Confirmação antes de excluir
        document.querySelectorAll('.btn-excluir').forEach(button => {
            button.addEventListener('click', function(e) {
                if(!confirm('Tem certeza que deseja excluir este item?')) {
                    preventDefault();
                }
            });
        });
    </script>
</body>
</html>