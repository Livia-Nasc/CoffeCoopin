<?php
session_start();
require '../vendor/autoload.php';
use Dompdf\Dompdf;

// Verificação de acesso
$tiposAcesso = [1,2];
$tipoUsuario = $_SESSION['usuario']['tipo'] ?? null;
if (!in_array($tipoUsuario, $tiposAcesso)) {
    header('location:login.php');
    exit();
}

// Conexão com o banco para obter dados dos garçons
require_once('../php/conexao.php');
$conn = getConexao();

// Obter garçons selecionados (se existirem)
$garconsSelecionados = $_SESSION['garcons_selecionados'] ?? [];
$dadosGarcons = [];
$totalComissao = 0;

if (!empty($garconsSelecionados)) {
    $placeholders = implode(',', array_fill(0, count($garconsSelecionados), '?'));
    $sql = "SELECT g.id, u.nome, g.salario_base, g.comissao 
            FROM usuario u 
            JOIN garcom g ON u.id = g.user_id
            WHERE g.id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($garconsSelecionados);
    $dadosGarcons = $stmt->fetchAll();
    
    // Calcular total da comissão
    foreach ($dadosGarcons as $garcom) {
        $totalComissao += $garcom['comissao'];
    }
}

// Configuração da data
date_default_timezone_set('America/Sao_Paulo');
$dataAtual = new DateTime();
$dataFormatada = $dataAtual->format('d/m/Y H:i:s');

// Criar PDF
$dompdf = new Dompdf([
    'isRemoteEnabled' => true,
    'chroot' => __DIR__,
]);

// HTML do relatório
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: "Muvia", "Poppins", sans-serif;
            background-color: #f9f0dd;
            color: #333;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
            letter-spacing: 0.9px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #C19770;
        }
        
        .header h1 {
            color: #C19770;
            font-size: 24pt;
            margin: 0;
        }
        
        .header .subtitle {
            color: #d5b895;
            font-size: 14pt;
            margin-top: 5px;
            font-family: "Poppins", sans-serif;
        }
        
        .content-box {
            background-color: #C19770;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 30px;
            margin: 20px auto;
            max-width: 600px;
        }
        
        .inner-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-family: "Poppins", sans-serif;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        table th {
            background-color: #C19770;
            color: white;
            padding: 12px;
            text-align: left;
            border: 1px solid #d5b895;
        }
        
        table td {
            padding: 10px;
            border: 1px solid #ddd;
            color: #333;
        }
        
        table tr:nth-child(even) {
            background-color: #f9f0dd;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10pt;
            color: #d5b895;
            border-top: 1px solid #d5b895;
            padding-top: 10px;
            font-family: "Poppins", sans-serif;
        }
        
        .text-right {
            text-align: right;
        }
        
        .bold {
            font-weight: bold;
        }
        
        .total-row {
            font-weight: bold;
            font-size: 1.1em;
            border-top: 2px solid #ddd;
            color: #C19770;
        }
        
        .total-row td {
            background-color: #f9f0dd;
        }
        
        .highlight {
            background-color: #f8dbc7;
            padding: 5px 10px;
            border-radius: 4px;
        }
        
        .garcom-list {
            margin: 15px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 4px;
        }
        
        .garcom-item {
            margin-bottom: 5px;
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Comissões</h1>
        <div class="subtitle">Gerado em '.$dataFormatada.' (Horário Local)</div>
    </div>
    
    <div class="content-box">
        <div class="inner-content">
            <h2>Comissões Calculadas</h2>
            
            <div class="garcom-list">
                <h3>Garçons incluídos no cálculo:</h3>';
                
if (!empty($dadosGarcons)) {
    foreach ($dadosGarcons as $garcom) {
        $html .= '<div class="garcom-item">'.htmlspecialchars($garcom['nome']).'</div>';
    }
} else {
    $html .= '<p>Nenhum garçom selecionado</p>';
}

$html .= '
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-right">Valor</th>
                    </tr>
                </thead>
                <tbody>';

// Adicionar linhas para cada garçom
if (!empty($dadosGarcons)) {
    $totalSalarioBase = 0;
    
    foreach ($dadosGarcons as $garcom) {
        $html .= '
        <tr>
            <td>Salário Base ('.htmlspecialchars($garcom['nome']).')</td>
            <td class="text-right">R$ '.number_format($garcom['salario_base'], 2, ',', '.').'</td>
        </tr>
        <tr>
            <td>Comissão ('.htmlspecialchars($garcom['nome']).')</td>
            <td class="text-right">R$ '.number_format($garcom['comissao'], 2, ',', '.').'</td>
        </tr>';
        
        $totalSalarioBase += $garcom['salario_base'];
    }
    
    $html .= '
        <tr class="total-row">
            <td>Total de Salários Base</td>
            <td class="text-right">R$ '.number_format($totalSalarioBase, 2, ',', '.').'</td>
        </tr>
        <tr class="total-row">
            <td>Total de Comissões</td>
            <td class="text-right">R$ '.number_format($totalComissao, 2, ',', '.').'</td>
        </tr>
        <tr class="total-row">
            <td>Total Geral a Pagar</td>
            <td class="text-right highlight">R$ '.number_format($totalSalarioBase + $totalComissao, 2, ',', '.').'</td>
        </tr>';
} else {
    $html .= '
        <tr>
            <td colspan="2" style="text-align: center;">Nenhum cálculo de comissão disponível</td>
        </tr>';
}

$html .= '
                </tbody>
            </table>
            
            <p>Observações: Os valores de comissão são calculados sobre o total de vendas realizadas por cada garçom no período.</p>
        </div>
    </div>
    
    <div class="footer">
        Sistema de Comissões - © '.date('Y').' Todos os direitos reservados
    </div>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_comissao.pdf", array("Attachment" => false));