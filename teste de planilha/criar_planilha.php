<?php
require_once('../php/conexao.php');
$conn = getConexao();
$sql = 'SELECT nome, telefone, email, cpf FROM usuario';
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=usuarios.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Write CSV header
fputcsv($output, ['Nome', 'Telefone', 'Email', 'CPF'], ';');

// Write data rows
foreach ($result as $row) {
    fputcsv($output, [
        $row['nome'],
        $row['telefone'],
        $row['email'],
        $row['cpf']
    ], ';');
}

// Close the output stream
fclose($output);
exit;