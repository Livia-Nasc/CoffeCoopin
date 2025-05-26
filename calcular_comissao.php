<?php
session_start();
$comissao = $_SESSION['comissao'] ?? 'Nenhum cálculo realizado ainda';

$tiposAcesso = [1,2];
$tipoUsuario = $_SESSION['usuario']['tipo'];
if (!in_array($tipoUsuario, $tiposAcesso)) {
    header('location:login.php');
    exit();
}
switch ($tipoUsuario) {
    case 1:
        $arquivo = 'dashboard/admin.php';
        break;
    case 2:
        $arquivo = 'dashboard/gerente.php';
        break;
}

// Conexão com o banco de dados e consulta dos garçons
require_once('php/conexao.php'); // Arquivo com a conexão PDO
$garcons = [];
$conn = getConexao();

$sql = "SELECT g.id, u.nome, u.cpf, u.email, u.telefone, u.data_nasc, g.escolaridade 
        FROM usuario u 
        JOIN garcom g 
        ON u.id = g.user_id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$garcons = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cálculo de Comissão</title>
    <link rel="stylesheet" href="css/conta.css">

    <style>
        body{
            overflow: hidden;   
        }
        button{
            padding: 12px 20px;
            margin: 5px 0;
            height: 3rem;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
            font-family: var(--fonte-principal);
            font-weight: 500;
        }
        
        select[multiple] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            height: auto;
            min-height: 120px;
        }
        
        .selected-garcons {
            margin: 10px 0;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 4px;
        }
        
        .selected-garcons h4 {
            margin-top: 0;
        }
        
        .selected-garcon {
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 10px;
            padding: 5px 10px;
            background: #e0e0e0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="-logo-container">
        <img src="img/logo.png" alt="Logo" class="logo-img">
    </div>
    <a href="<?php echo $arquivo?>" class="btn-voltar">Voltar</a>

    <div id="container">
        <div id="box">
            <div class="cadastro">
                <div class="form-container">
                    <h2>Cálculo de Comissão</h2>
                    
                    <form action="php/gerente.php" method="post" id="comissaoForm">
                        <label for="garcom">Selecione um ou mais garçons</label>
                        <select name="garcom_id[]" id="garcom" multiple required>
                            <?php foreach ($garcons as $garcom): ?>
                                <option value="<?php echo $garcom['id']; ?>"
                                    <?php echo (isset($_SESSION['garcons_selecionados']) && in_array($garcom['id'], $_SESSION['garcons_selecionados'])) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($garcom['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        
                        <div class="selected-garcons" id="selectedGarcons">
                            <h4>Garçons selecionados:</h4>
                            <?php if(isset($_SESSION['garcons_selecionados']) && is_array($_SESSION['garcons_selecionados'])): ?>
                                <?php foreach($_SESSION['garcons_selecionados'] as $id): 
                                    $garcom = array_filter($garcons, function($g) use ($id) { return $g['id'] == $id; });
                                    if (!empty($garcom)) {
                                        $garcom = reset($garcom);
                                    ?>
                                    <div class="selected-garcon">
                                        <?php echo htmlspecialchars($garcom['nome']); ?>
                                        <input type="hidden" name="garcom_id[]" value="<?php echo $id; ?>">
                                    </div>
                                <?php } endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" name="calcular_comissao" class="btn btn-primary">
                            Calcular Comissão
                        </button>
                    </form>
                    
                    <div class="resultado-comissao">
                        <h3>Resultado:</h3>
                        <p><?php echo $comissao; ?></p>
                    </div>
                </div>
            </div>
        </div>
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
        // Atualiza a lista de garçons selecionados quando o select é alterado
        document.getElementById('garcom').addEventListener('change', function() {
            const selectedOptions = Array.from(this.selectedOptions).map(option => ({
                id: option.value,
                nome: option.text   
            }));
            
            const selectedContainer = document.getElementById('selectedGarcons');
            selectedContainer.innerHTML = '<h4>Garçons selecionados:</h4>';
            
            selectedOptions.forEach(garcom => {
                const div = document.createElement('div');
                div.className = 'selected-garcon';
                div.innerHTML = `
                    ${garcom.nome}
                    <input type="hidden" name="garcom_id[]" value="${garcom.id}">
                `;
                selectedContainer.appendChild(div);
            });
        });
    </script>
</body>
</html>