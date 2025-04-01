<?php
require_once('conexao.php');
session_start();

// Verifica se é gerente ou admin
if($_SESSION['usuario']['tipo'] == 3) { // Garçom não pode acessar
    header('Location: ../login.php');
    exit();
}

// Relatório de vendas
if(isset($_POST['relatorio_vendas'])) {
    $conn = getConexao();
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    
    $sql = "SELECT p.nome, SUM(pe.quantidade) as total, SUM(pe.quantidade * p.preco) as valor_total
            FROM pedido pe
            JOIN produto p ON pe.produto_id = p.id
            WHERE pe.data_hora BETWEEN ? AND ?
            GROUP BY p.nome";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$data_inicio, $data_fim]);
    
    $_SESSION['relatorio_vendas'] = $stmt->fetchAll();
    header('Location: ../relatorios.php');
    exit();
}

// Relatório de comissões
if(isset($_POST['relatorio_comissoes'])) {
    $conn = getConexao();
    $mes = $_POST['mes'];
    $ano = $_POST['ano'];
    
    $sql = "SELECT u.nome, COUNT(c.id) as total_contas, SUM(c.valor_total) as valor_total,
            SUM(c.valor_total) * 0.1 as comissao
            FROM conta c
            JOIN usuario u ON c.garcom_id = u.id
            WHERE MONTH(c.data_fechamento) = ? AND YEAR(c.data_fechamento) = ?
            GROUP BY u.nome";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$mes, $ano]);
    
    $_SESSION['relatorio_comissoes'] = [
        'dados' => $stmt->fetchAll(),
        'mes' => $mes,
        'ano' => $ano
    ];
    header('Location: ../comissoes.php');
    exit();
}
?>