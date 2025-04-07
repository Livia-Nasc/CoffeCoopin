<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
#produtos {
    text-align:center;
  margin-top: 10px;
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: fit-content;
  display: flex;
  align-self: center;
  justify-self: center;
  flex-direction: column;

}
th{
    margin-top:20px;
    font-weight: 600;
}

td, th {
  border: 1px solid #000;
  text-align: center;
  padding: 8px;
}
#visualizar{
  background-color:rgb(241, 197, 152);
  margin-bottom:20px;
}
</style>
</head>
<body>
    <h2>abrir conta</h2>
    <form method="post" action="php/conta.php">
        <input type="number" name="mesa" placeholder="Número da mesa" required>
        <input type="number" name="garcom_id" placeholder="ID Garçom" required>
        <button type="submit" name="abrir_conta" class="btn btn-primary">Abrir</button>
    </form>
    <form action="php/conta.php" method="post">
        <button type="submit" name = "visualizar" id="visualizar">Visualizar produtos</button>
    </form>
    <div id="produtos">
    <table>
        <tr>
            <th>mesa</th>
            <th>garçom_id</th>
            <th>data_abertura</th>
            <th>status</th>
            <th>id</th>
        </tr>
    <?php
        if(isset($_SESSION['conta'])) {
            foreach ($_SESSION['conta'] as $conta) {
                $nome = $conta['mesa'] ?? '';
                $porcao = $conta['garcom_id'] ?? '';
                $qtd_estoque = $conta['data_abertura'] ?? 0;
                $categoria = $conta['status'] ?? '';
                $preco = $conta['id'] ?? 0.0;
        ?>
        <tr>
            <td><?php echo $nome; ?></td>
            <td><?php echo $porcao; ?></td>
            <td><?php echo $qtd_estoque; ?></td>
            <td><?php echo $categoria; ?></td>
            <td><?php echo $preco; ?></td>
        </tr>
    <?php
        }
    }
    ?>
    </table>
</div>
</body>
</html>