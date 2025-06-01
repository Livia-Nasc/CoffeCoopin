<?php
require './vendor/autoload.php';
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
    'chroot' => __DIR__,
]);

// Inicia a construção do HTML
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* ESTILOS COM CORES DIRETAS - SEM VARIÁVEIS */
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
        
        h2, h3 {
            color: #333;
            margin: 0 0 20px 0;
            text-align: center;
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
        
        .garcom-header {
            background-color: #d5b895;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Comissões</h1>
        <div class="subtitle">Gerado em '.$dataFormatada.'</div>
    </div>
    
    <div class="content-box">
        <div class="inner-content">
            <h2>Detalhamento por Garçom</h2>';
            
// Adiciona uma tabela para cada garçom
foreach ($resultados as $resultado) {
    $html .= '
            <h3>'.$resultado['nome'].'</h3>
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
            </table>';
}

// Adiciona o resumo geral
$html .= '
            <h2>Resumo Geral</h2>
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
            
            <p>Observações: O valor da comissão é calculado como 10% sobre o total de vendas realizadas por cada garçom.</p>
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