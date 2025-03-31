<?php

// Definir o cabeçalho correto para UTF-8
header('Content-Type: text/html; charset=UTF-8');

// Caminho do arquivo CSV
$arquivo_csv = '../arquivo.csv'; // Caminho do arquivo CSV de entrada

// Abrir o arquivo CSV original
if (($handle = fopen($arquivo_csv, 'r')) !== FALSE) {
    
    // Ler o cabeçalho do arquivo CSV original
    $cabecalho = fgetcsv($handle, 1000, ';');
    
    // Converter o cabeçalho para UTF-8 se necessário
    $cabecalho = array_map(function($item) {
        return mb_convert_encoding($item, "UTF-8", "ISO-8859-1"); // Ajuste para UTF-8
    }, $cabecalho);
    
    // Iniciar a tabela HTML
    echo '<table border="1">';
    
    // Exibir o cabeçalho da tabela
    echo '<tr>';
    foreach ($cabecalho as $campo) {
        echo '<th>' . htmlspecialchars($campo, ENT_QUOTES, 'UTF-8') . '</th>';
    }
    echo '</tr>';
    
    // Ler as linhas do arquivo CSV e exibir na tabela
    while (($row = fgetcsv($handle, 1000, ';')) !== FALSE) {
        echo '<tr>';
        foreach ($row as $campo) {
            // Converter cada campo para UTF-8
            $campo = mb_convert_encoding($campo, "UTF-8", "ISO-8859-1");
            echo '<td>' . htmlspecialchars($campo, ENT_QUOTES, 'UTF-8') . '</td>';
        }
        echo '</tr>';
    }

    // Fechar o arquivo CSV original
    fclose($handle);
    
    // Fechar a tabela
    echo '</table>';
}
?>
