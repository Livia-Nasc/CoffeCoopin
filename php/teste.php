<?php
require_once('conexao.php');
session_start();

// Verifica se o formulário foi enviado
if(isset($_POST['calcular'])) {
    try {
        $conn = getConexao(); // Função que retorna uma conexão PDO
        
        // Pega os dados do formulário
        $mes = $_POST['mes'];
        $ano = $_POST['ano'];
        
        // Validações básicas
        if(empty($mes) || empty($ano)) {
            throw new Exception("Mês e ano são obrigatórios!");
        }

        // Calcula o primeiro e último dia do mês
        $primeiro_dia = "$ano-$mes-01";
        $ultimo_dia = date("Y-m-t", strtotime($primeiro_dia));
        
        // Prepara a consulta SQL
        $sql = "SELECT u.id, u.nome, SUM(c.valor_total) as total 
                FROM conta c 
                INNER JOIN usuario u ON c.garcom_id = u.id 
                WHERE c.status = 'fechada' 
                AND c.data_fechamento BETWEEN :inicio AND :fim";
        
        // Adiciona filtro por garçom se selecionado
        if(isset($_POST['garcom_id']) && !empty($_POST['garcom_id'])) {
            $garcom_id = $_POST['garcom_id'];
            $sql .= " AND c.garcom_id = :garcom_id";
        }
        
        $sql .= " GROUP BY u.id, u.nome";
        
        // Prepara e executa a consulta
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':inicio', $primeiro_dia);
        $stmt->bindParam(':fim', $ultimo_dia);
        
        if(isset($garcom_id)) {
            $stmt->bindParam(':garcom_id', $garcom_id, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        
        // Processa os resultados
        $comissoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total_geral = 0;
        
        foreach($comissoes as &$comissao) {
            $comissao['comissao'] = $comissao['total'] * 0.1;
            $total_geral += $comissao['comissao'];
        }
        
        // Guarda na sessão para mostrar na página
        $_SESSION['comissoes'] = $comissoes;
        $_SESSION['periodo'] = [
            'inicio' => $primeiro_dia,
            'fim' => $ultimo_dia,
            'total_geral' => $total_geral
        ];
        
        // Redireciona de volta
        header("Location: ../teste.php?ok=1");
        exit;
        
    } catch(Exception $e) {
        $_SESSION['erro'] = "Erro ao calcular comissões: " . $e->getMessage();
        header("Location: ../teste.php");
        exit;
    }
}

// Se quiser salvar no banco de dados
if(isset($_POST['salvar'])) {
    try {
        $conn = getConexao();
        
        if(!isset($_SESSION['comissoes'])) {
            throw new Exception("Nenhuma comissão calculada para salvar!");
        }
        
        $conn->beginTransaction();
        
        foreach($_SESSION['comissoes'] as $comissao) {
            $sql = "INSERT INTO historico_comissao 
                   (garcom_id, mes_referencia, total_vendido, valor_comissao, data_calculo)
                   VALUES
                   (:garcom_id, :mes_ref, :total, :comissao, NOW())";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':garcom_id', $comissao['id'], PDO::PARAM_INT);
            $stmt->bindParam(':mes_ref', $_SESSION['periodo']['inicio']);
            $stmt->bindParam(':total', $comissao['total']);
            $stmt->bindParam(':comissao', $comissao['comissao']);
            $stmt->execute();
        }
        
        $conn->commit();
        $_SESSION['sucesso'] = "Comissões salvas com sucesso!";
        
    } catch(Exception $e) {
        $conn->rollBack();
        $_SESSION['erro'] = "Erro ao salvar comissões: " . $e->getMessage();
    }
    
    header("Location: ../teste.php");
    exit;
}
?>