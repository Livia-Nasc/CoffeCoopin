<?php
require_once('../php/conexao.php');
$conn = getConexao();
$sql = 'SELECT nome, telefone, email, cpf FROM usuario';
$stmt = $conn -> prepare($sql);
$result = $stmt->fetchAll();

//echo "<h1>Gerar Excel - csv</h1>";

// Aceitar csv ou texto
header('Content-Type: text/csv; charset=utf-8');

// Nome arquivo
header('Content-Disposition: attachment; filename=arquivo.csv');

// Gravar no buffer
$resultado = fopen("php://output", 'w');

// Criar o cabeçalho do Excel - Usar a função mb_convert_encoding para converter carateres especiais
$cabecalho = ['Nome', 'Telefone', 'Email', 'Cpf'];

// Array de dados
foreach ($result as $value) {
    $usuarios = [
        [
            'Nome' => $value['nome'],
            'Telefone' => $value['telefone'],
           'Email' => $value['email'],
            'Cpf' => $value['cpf']
        ]
        ];
        };


// Abrir o arquivo
//$arquivo = fopen('file.csv', 'w');

// Escrever o cabeçalho no arquivo
fputcsv($resultado, $cabecalho, ';');

// Escrever o conteúdo no arquivo
foreach($usuarios as $row_usuario){
    fputcsv($resultado, $row_usuario, ';');
}

// Fechar arquivo
fclose($resultado);
