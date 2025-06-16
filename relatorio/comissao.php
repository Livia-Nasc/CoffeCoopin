<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;

session_start();

// Verifica se existem dados de comissão na sessão
if (!isset($_SESSION['relatorio_comissao'])) {
    header('Location: calcular_comissao.php');
    exit();
}

$dados = $_SESSION['relatorio_comissao'];
$resultados = $dados['resultados'];
$total_comissao = $dados['total_comissao'];
$total_salario = $dados['total_salario'];
$dataFormatada = $dados['data'];

$dompdf = new Dompdf([
    'isRemoteEnabled' => true,
]);

// Inicia a construção do HTML
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 15mm 20mm;
        }
        
        body {
            font-family: "Helvetica", "Arial", sans-serif;
            color: #333;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #C19770;
        }
        
        .header h1 {
            color: #C19770;
            font-size: 24pt;
            margin: 0 0 5px 0;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 14pt;
            margin: 0;
        }
        
        .header .info {
            color: #888;
            font-size: 11pt;
            margin-top: 10px;
        }
        
        .content-box {
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 11pt;
        }
        
        table th {
            background-color: #C19770;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #d5b895;
        }
        
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10pt;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .garcom-section {
            margin-bottom: 25px;
        }
        
        .garcom-title {
            background-color: #f5f5f5;
            padding: 8px;
            border-left: 4px solid #C19770;
            margin-bottom: 10px;
        }
        
        .summary {
            margin-top: 10px;
            font-style: italic;
            color: #666;
        }
        
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Comissões</h1>
        <div class="subtitle">Detalhamento de Pagamentos</div>
        <div class="info">Gerado em: '.$dataFormatada.'</div>
    </div>
    
    <div class="content-box">';

// Adiciona uma seção para cada garçom
foreach ($resultados as $resultado) {
    $html .= '
        <div class="garcom-section">
            <div class="garcom-title">'.$resultado['nome'].'</div>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-right">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Vendas Totais</td>
                        <td class="text-right">R$ '.$resultado['valor_vendas_formatado'].'</td>
                    </tr>
                    <tr>
                        <td>Salário Base</td>
                        <td class="text-right">R$ '.number_format($resultado['salario_base'], 2, ',', '.').'</td>
                    </tr>
                    <tr>
                        <td>Comissão (10% das vendas)</td>
                        <td class="text-right">R$ '.$resultado['comissao_formatado'].'</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total a Receber</td>
                        <td class="text-right">R$ '.$resultado['salario_total_formatado'].'</td>
                    </tr>
                </tbody>
            </table>
        </div>';
}

// Adiciona o resumo geral
$html .= '
        <div class="garcom-section">
            <div class="garcom-title">Resumo Geral</div>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-right">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total de Garçons</td>
                        <td class="text-right">'.count($resultados).'</td>
                    </tr>
                    <tr>
                        <td>Total em Comissões</td>
                        <td class="text-right">R$ '.number_format($total_comissao, 2, ',', '.').'</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total Geral a Pagar</td>
                        <td class="text-right">R$ '.number_format($total_salario, 2, ',', '.').'</td>
                    </tr>
                </tbody>
            </table>
            
            <div class="summary">
                Observações: O valor da comissão é calculado como 10% sobre o total de vendas realizadas por cada garçom.
            </div>
        </div>
    </div>
    
    <div class="footer">
        CoopinCoffee - © '.date('Y').' - Todos os direitos reservados
    </div>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_comissao.pdf", array("Attachment" => false));