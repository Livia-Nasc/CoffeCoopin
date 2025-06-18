<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;
date_default_timezone_set('America/Sao_Paulo');
session_start();

$tiposAcesso = [1,2];
$tipoUsuario = $_SESSION['usuario']['tipo'];
if (!in_array($tipoUsuario, $tiposAcesso)) {
    header('location:login.php');
    exit();
}

require_once('../php/conexao.php');
$conn = getConexao();

$data_inicio = $_GET['data_inicio'] ?? date('Y-m-01');
$data_fim = $_GET['data_fim'] ?? date('Y-m-t');
$tipo_relatorio = $_GET['tipo_relatorio'] ?? 'historico';

if ($tipo_relatorio == 'historico') {
    $sql = "SELECT c.mesa, u.nome as garcom, 
                   SUM(p.preco * pd.quantidade) as total_vendido,
                   COUNT(DISTINCT c.id) as qtd_contas
            FROM conta c
            JOIN garcom g ON c.garcom_id = g.id
            JOIN usuario u ON g.user_id = u.id
            JOIN pedido pd ON c.id = pd.conta_id
            JOIN produto p ON pd.produto_id = p.id
            WHERE c.data_abertura BETWEEN :data_inicio AND :data_fim
            GROUP BY c.mesa, u.nome
            ORDER BY c.mesa";
    
    $titulo = "Histórico de Mesas de " . date('d/m/Y', strtotime($data_inicio)) . " a " . date('d/m/Y', strtotime($data_fim));
} else {
    $sql = "SELECT c.mesa, u.nome as garcom, 
                   c.valor_total as total_vendido,
                   DATE_FORMAT(c.data_abertura, '%d/%m/%Y') as data_abertura,
                   TIME_FORMAT(c.hora_abertura, '%H:%i') as hora_abertura
            FROM conta c
            JOIN garcom g ON c.garcom_id = g.id
            JOIN usuario u ON g.user_id = u.id
            WHERE c.status = 'aberta'
            ORDER BY c.mesa";
    
    $titulo = "Mesas Atualmente Abertas";
}

$stmt = $conn->prepare($sql);
if ($tipo_relatorio == 'historico') {
    $stmt->bindParam(':data_inicio', $data_inicio);
    $stmt->bindParam(':data_fim', $data_fim);
}
$stmt->execute();
$mesas = $stmt->fetchAll();

$total_mesas = count($mesas);
$total_vendido = array_sum(array_column($mesas, 'total_vendido'));

$dompdf = new Dompdf(['isRemoteEnabled' => true]);

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
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Ocupação de Mesas</h1>
        <div class="subtitle">'.$titulo.'</div>
        <div class="info">Gerado em: '.date('d/m/Y H:i:s').'</div>
    </div>
    
    <div class="content-box">
        <table>
            <thead>
                <tr>
                    <th>Mesa</th>
                    <th>Garçom</th>
                    '.($tipo_relatorio == 'historico' ? '<th>Qtd. Contas</th>' : '<th>Data Abertura</th><th>Hora Abertura</th>').'
                    <th>Total Vendido</th>
                </tr>
            </thead>
            <tbody>';
            
foreach ($mesas as $mesa) {
    $html .= '
                <tr>
                    <td>'.$mesa['mesa'].'</td>
                    <td>'.htmlspecialchars($mesa['garcom']).'</td>
                    '.($tipo_relatorio == 'historico' ? '<td>'.$mesa['qtd_contas'].'</td>' : '<td>'.$mesa['data_abertura'].'</td><td>'.$mesa['hora_abertura'].'</td>').'
                    <td>R$ '.number_format($mesa['total_vendido'], 2, ',', '.').'</td>
                </tr>';
}

$html .= '
                <tr class="total-row">
                    <td colspan="'.($tipo_relatorio == 'historico' ? 3 : 4).'"><strong>Total</strong></td>
                    <td><strong>R$ '.number_format($total_vendido, 2, ',', '.').'</strong></td>
                </tr>
            </tbody>
        </table>
        
        <div class="summary">
            Total de mesas: '.$total_mesas.'
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
$dompdf->stream("relatorio_mesas.pdf", array("Attachment" => false));