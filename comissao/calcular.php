<?php
session_start();

$tiposAcesso = [1,2];
$tipoUsuario = $_SESSION['usuario']['tipo'];
if (!in_array($tipoUsuario, $tiposAcesso)) {
    header('location:login.php');
    exit();
}
switch ($tipoUsuario) {
    case 1:
        $arquivo = '../dashboard/admin.php';
        break;
    case 2:
        $arquivo = '../dashboard/gerente.php';
        break;
}

require_once('../php/conexao.php');
$conn = getConexao();

// Busca lista de garçons
$sql = "SELECT g.id, u.nome FROM usuario u JOIN garcom g ON u.id = g.user_id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$garcons = $stmt->fetchAll();

// Define o mês atual como padrão
$mesAtual = date('Y-m');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cálculo de Comissão</title>
    <link rel="stylesheet" href="../css/conta.css">
</head>
<body>
    <div class="-logo-container">
        <img src="../img/logo.png" alt="Logo" class="logo-img">
    </div>
    <a href="<?php echo $arquivo?>" class="btn-voltar">Voltar</a>

    <div id="container">
        <div id="box">
            <div class="form-container">
                <h2>Cálculo de Comissão</h2>
                
                <form action="../php/gerente.php" method="post" id="comissaoForm">
                    <div class="form-group">
                        <label for="mes_referencia">Mês de Referência:</label>
                        <input type="month" name="mes_referencia" id="mes_referencia" 
                               value="<?= $mesAtual ?>" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="garcom">Selecione um ou mais garçons:</label>
                        <select name="garcom_ids[]" id="garcom" multiple required class="form-control">
                            <?php foreach ($garcons as $garcom){ ?>
                                <option value="<?= $garcom['id'] ?>">
                                    <?= htmlspecialchars($garcom['nome']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <button type="submit" name="calcular_comissao" class="btn btn-primary">
                        Calcular Comissão
                    </button>
                </form>
                
                <?php if(isset($_SESSION['mensagem'])){ ?>
                    <div class="mensagem-alerta">
                        <?php 
                            echo $_SESSION['mensagem'];
                            unset($_SESSION['mensagem']); 
                        ?>
                    </div>
                <?php } ?>
                
                <?php if(isset($_SESSION['relatorio_comissao'])): ?>
                    <div class="resultado-comissao">
                        <h3>Resultado:</h3>
                        <p><?= $_SESSION['comissao'] ?? 'Nenhum cálculo realizado ainda' ?></p>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <a href="../relatorio/comissao.php" class="btn btn-primary">Gerar Relatório PDF</a>
                        <form action="../php/gerente.php" method="post" style="display: inline;">
                            <input type="hidden" name="salvar_comissao" value="1">
                            <button type="submit" class="btn btn-primary">Salvar no Histórico</button>
                        </form>
                    </div>
                <?php elseif(isset($_SESSION['comissao'])): ?>
                    <div class="resultado-comissao">
                        <h3>Resultado:</h3>
                        <p><?= $_SESSION['comissao'] ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('garcom').addEventListener('change', function() {
            const selectedOptions = Array.from(this.selectedOptions).map(option => ({
                id: option.value,
                nome: option.text   
            }));
            
            const selectedContainer = document.createElement('div');
            selectedContainer.id = 'selectedGarcons';
            selectedContainer.innerHTML = '<h4>Garçons selecionados:</h4>';
            
            selectedOptions.forEach(garcom => {
                const div = document.createElement('div');
                div.className = 'selected-garcon';
                div.textContent = garcom.nome;
                selectedContainer.appendChild(div);
            });
            
            // Remove o container anterior se existir
            const oldContainer = document.getElementById('selectedGarcons');
            if (oldContainer) {
                oldContainer.remove();
            }
            
            // Insere o novo container após o select
            this.parentNode.insertBefore(selectedContainer, this.nextSibling);
        });
    </script>
</body>
</html>