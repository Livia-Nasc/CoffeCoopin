<?php
session_start();
require_once('php/conexao.php');

// Apenas admin pode ver
if(!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] != 1) {
    header('Location: login.php');
    exit();
}

$conn = getConexao();
$sql = "SELECT u.*, g.rg 
        FROM usuario u
        LEFT JOIN gerente g ON u.id = g.user_id
        ORDER BY u.tipo, u.nome";
$usuarios = $conn->query($sql)->fetchAll();

function getTipoUsuario($tipo) {
    switch($tipo) {
        case 1: return 'Administrador';
        case 2: return 'Gerente';
        case 3: return 'Garçom';
        default: return 'Cliente';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Lista de Usuários</h2>
    
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>CPF</th>
                <th>Tipo</th>
                <th>RG (Gerente)</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario['nome'] ?></td>
                <td><?= $usuario['email'] ?></td>
                <td><?= $usuario['cpf'] ?></td>
                <td><?= getTipoUsuario($usuario['tipo']) ?></td>
                <td><?= $usuario['rg'] ?? '-' ?></td>
                <td>
                    <?php if($usuario['tipo'] == 3): ?>
                    <a href="cadastro_garcom.php?editar=<?= $usuario['id'] ?>" class="btn btn-info btn-sm">Editar</a>
                    <?php elseif($usuario['tipo'] == 2): ?>
                    <a href="cadastro_gerente.php?editar=<?= $usuario['id'] ?>" class="btn btn-info btn-sm">Editar</a>
                    <?php endif; ?>
                    <form method="post" action="php/usuario.php" style="display:inline;">
                        <input type="hidden" name="usuario_id" value="<?= $usuario['id'] ?>">
                        <button type="submit" name="excluir" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Excluir</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="mt-3">
        <a href="cadastro_gerente.php" class="btn btn-primary">Cadastrar Gerente</a>
        <a href="cadastro_garcom.php" class="btn btn-primary">Cadastrar Garçom</a>
        <a href="admin.php" class="btn btn-secondary">Voltar</a>
    </div>
</div>

</body>
</html>