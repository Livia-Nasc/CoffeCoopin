<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* ESTILOS COM CORES DIRETAS - SEM VARIÁVEIS */
        body {
            font-family: "Muvia", "Poppins", sans-serif;
            background-color: #f9f0dd; /* COR FUNDO */
            color: #333; /* COR TEXTO */
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
            border-bottom: 2px solid #C19770; /* COR PRINCIPAL */
        }
        
        .header h1 {
            color: #C19770; /* COR PRINCIPAL */
            font-size: 24pt;
            margin: 0;
        }
        
        .header .subtitle {
            color: #d5b895; /* COR SECUNDÁRIA */
            font-size: 14pt;
            margin-top: 5px;
            font-family: "Poppins", sans-serif;
        }
        
        .content-box {
            background-color: #C19770; /* COR PRINCIPAL */
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
            background-color: #C19770; /* COR PRINCIPAL */
            color: white;
            padding: 12px;
            text-align: left;
            border: 1px solid #d5b895; /* COR SECUNDÁRIA */
        }
        
        table td {
            padding: 10px;
            border: 1px solid #ddd;
            color: #333; /* COR TEXTO */
        }
        
        table tr:nth-child(even) {
            background-color: #f9f0dd; /* COR FUNDO */
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10pt;
            color: #d5b895; /* COR SECUNDÁRIA */
            border-top: 1px solid #d5b895; /* COR SECUNDÁRIA */
            padding-top: 10px;
            font-family: "Poppins", sans-serif;
        }
        
        .btn {
            padding: 10px 15px;
            margin: 10px 5px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            font-family: "Poppins", sans-serif;
            display: inline-block;
            min-width: 150px;
            transition: background-color 0.3s;
        }
        
        .btn-primary {
            background-color: #C19770; /* COR PRINCIPAL */
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #d5b895; /* COR SECUNDÁRIA */
        }
        
        h2, h3 {
            color: #333; /* COR TEXTO */
            margin: 0 0 20px 0;
            text-align: center;
        }
        
        p {
            font-family: "Poppins", sans-serif;
            margin-bottom: 15px;
        }
        
        .highlight {
            background-color: #f8dbc7; /* COR SOMBRA */
            padding: 5px 10px;
            border-radius: 4px;
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
            color: #C19770; /* COR PRINCIPAL */
        }
        
        .total-row td {
            background-color: #f9f0dd; /* COR FUNDO */
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório Financeiro</h1>
        <div class="subtitle">Gerado em '.$dataFormatada.' (Horário Local)</div>
    </div>
    
    <div class="content-box">
        <div class="inner-content">
            <h2>Comissão Calculada</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-right">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Salário Base</td>
                        <td class="text-right">R$ 1.000,00</td>
                    </tr>
                    <tr>
                        <td>Comissão (10% das vendas)</td>
                        <td class="text-right">R$ 99.999.999,97</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total a Receber</td>
                        <td class="text-right highlight">R$ 100.000.999,97</td>
                    </tr>
                </tbody>
            </table>
            
            <p>Observações: O valor da comissão é calculado sobre o total de vendas realizadas no período.</p>
            
            <div style="text-align: center;">
                <button class="btn btn-primary">Imprimir Relatório</button>
            </div>
        </div>
    </div>
    
    <div class="footer">
        Sistema de Comissões - © '.date('Y').' Todos os direitos reservados
    </div>
</body>
</html>