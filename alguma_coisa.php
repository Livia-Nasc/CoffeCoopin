<!-- Botar na função -->
<?php
$disciplinas = "SELECT disciplinas.nome AS disciplinas_nome
            FROM usuarios
            JOIN disciplinas ON usuarios.disciplinas_id = disciplinas.id ORDER BY disciplinas_nome";

    $disciplinas = $disciplina ?? null;

    if ($disciplinas_id === null) {
        return "Disciplina inválida.";
    }
?>

<!-- botar no select -->
    <?php
      $query = $pdo->query("SELECT id, nome FROM disciplinas ORDER BY nome");
      while($reg = $query->fetch()) {
        echo '<option value="'.$reg["id"].'">'.$reg["nome"].'</option>';
      }
    ?>   