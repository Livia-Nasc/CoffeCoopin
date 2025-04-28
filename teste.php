<?php
require_once('php/conexao.php');
session_start();

// Verifica mensagens de status
$sucesso = $_SESSION['sucesso'] ?? null;
$erro = $_SESSION['erro'] ?? null;

// Limpa as mensagens após exibir
unset($_SESSION['sucesso']);
unset($_SESSION['erro']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cálculo de Comissões - Restaurante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-box {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            background: #f9f9f9;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
            background-color: #e6e6e6;
        }
        .alert {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        .alert-error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        .btn {
            padding: 8px 16px;
            margin-right: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Cálculo de Comissões</h1>
    
    <?php if($sucesso): ?>
        <div class="alert alert-success"><?php echo $sucesso; ?></div>
    <?php endif; ?>
    
    <?php if($erro): ?>
        <div class="alert alert-error"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <div class="form-box">
        <h2>Calcular Comissões</h2>
        <form method="post" action="php/teste.php">
            <div style="margin-bottom: 15px;">
                <label for="mes" style="display: block; margin-bottom: 5px;">Mês:</label>
                <select name="mes" id="mes" required style="padding: 8px; width: 100%;">
                    <option value="">Selecione</option>
                    <?php for($i=1; $i<=12; $i++): ?>
                        <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>">
                            <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="ano" style="display: block; margin-bottom: 5px;">Ano:</label>
                <input type="number" name="ano" id="ano" min="2020" max="2030" 
                       value="<?php echo date('Y'); ?>" required 
                       style="padding: 8px; width: 100%;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="garcom_id" style="display: block; margin-bottom: 5px;">Garçom (opcional):</label>
                <select name="garcom_id" id="garcom_id" style="padding: 8px; width: 100%;">
                    <option value="">Todos os Garçons</option>
                    <?php
                    try {
                        $conn = getConexao();
                        $stmt = $conn->query("SELECT u.id, u.nome FROM usuario u INNER JOIN garcom g ON u.id = g.user_id");
                        while($garcom = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="'.$garcom['id'].'">'.$garcom['nome'].'</option>';
                        }
                    } catch(PDOException $e) {
                        echo "<!-- Erro ao carregar garçons: ".$e->getMessage()." -->";
                    }
                    ?>
                </select>
            </div>
            
            <div>
                <button type="submit" name="calcular" class="btn">Calcular Comissões</button>
            </div>
        </form>
    </div>
    
    <?php if(isset($_SESSION['comissoes']) && !empty($_SESSION['comissoes'])): ?>
        <h2>Resultado das Comissões</h2>
        <p>Período: <?php echo date('d/m/Y', strtotime($_SESSION['periodo']['inicio'])).' a '.date('d/m/Y', strtotime($_SESSION['periodo']['fim'])); ?></p>
        
        <table>
            <thead>
                <tr>
                    <th>Garçom</th>
                    <th>Total Vendido</th>
                    <th>Comissão (10%)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($_SESSION['comissoes'] as $comissao): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($comissao['nome']); ?></td>
                        <td>R$ <?php echo number_format($comissao['total'], 2, ',', '.'); ?></td>
                        <td>R$ <?php echo number_format($comissao['comissao'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="2"><strong>Total Geral em Comissões</strong></td>
                    <td><strong>R$ <?php echo number_format($_SESSION['periodo']['total_geral'], 2, ',', '.'); ?></strong></td>
                </tr>
            </tbody>
        </table>
        
        <form method="post" action="calcular_comissoes.php" style="margin-top: 20px;">
            <button type="submit" name="salvar" class="btn">Salvar Comissões</button>
            <button type="button" onclick="window.print()" class="btn">Imprimir Relatório</button>
        </form>
    <?php endif; ?>
</body>
</html>