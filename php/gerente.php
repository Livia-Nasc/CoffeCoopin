<?php
require_once("conexao.php");
require("usuario.php");
date_default_timezone_set('America/Sao_Paulo');
session_start();
function CadastrarGerente()
{
    $conn = getConexao();

    $nome = ucwords(strtolower($_POST['nome']));
    $telefone = $_POST['telefone'];
    $data_nasc = $_POST['data_nasc'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $cpf = $_POST['cpf'];
    $rg = $_POST['rg'];
    $cpf_novo = preg_replace('/[^0-9]/', '', $cpf);

    $sql = 'SELECT * FROM usuario WHERE cpf = :cpf OR email = :email'; // ! Seleciona dados usando o CPF e o email do usuário a ser cadastrado
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':cpf', $cpf_novo);
    $stmt->execute();

    if ($stmt->rowcount() == 0) { // ! Verifica se o CPF e o e-mail não estão registrados

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $cadastro = 'INSERT INTO usuario(nome, telefone, data_nasc, email, senha, cpf, tipo) VALUES( :nome, :telefone, :data_nasc,:email, :senha, :cpf, 2)'; // ! Insere dados passados no formulário contido na página cadastro.php na tabela usuario
        $stmt = $conn->prepare($cadastro);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cpf', $cpf_novo);

        if ($stmt->execute()) {
            $user_id = $conn->lastInsertId();

            // ! Insere os dados na tabela gerente
            $cadastroGerente = 'INSERT INTO gerente(rg, user_id) VALUES(:rg, :user_id)';
            $stmt = $conn->prepare($cadastroGerente);
            $stmt->bindParam(':rg', $rg);
            $stmt->bindParam(':user_id', $user_id);
            if ($stmt->execute()) {
                header('location: ../cadastro/gerente.php'); // ! Vai para a página de login
                exit();
            } else {
                echo "Erro ao cadastrar usuário.";
            }
        }
    } else {
        echo "<script type='text/javascript'>
                        alert('Informações já existentes');  // ! Se o CPF e o e-mail já existirem, exibe mensagem de erro na página cadastro.php
                        window.location='../cadastro/gerente.php';
                      </script>";
    }
}

function VisualizarGerente(){
    unset($_SESSION['gerente']);
    $nome = strtoupper($_POST['nome'] ?? '');
    session_start();
    $conn = getConexao();
    if ($nome != '') {
        $sql = "SELECT g.id, u.nome, u.cpf, u.email, u.telefone, u.data_nasc, g.rg
            FROM usuario u 
            JOIN gerente g 
            ON u.id = g.user_id
            WHERE u.nome LIKE :nome" ;
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nome', "%$nome%");
        $stmt->execute();
        
        $_SESSION['gerente'] = $stmt->fetchAll();
        $_SESSION['mensagem'] = $stmt->rowCount() ? "" : "Nenhum gerente encontrado";
    } else{
        $conn = getConexao();
        $sql = "SELECT g.id, u.nome, u.cpf, u.email, u.telefone, u.data_nasc, g.rg 
            FROM usuario u 
            JOIN gerente g 
            ON u.id = g.user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $_SESSION['gerente'] = $stmt->fetchAll();
        
    }
    header('location: ../visualização/gerentes.php'); 
    exit();
}

// Adicione esta função ao arquivo gerente.php
function SalvarComissao() {
    $conn = getConexao();
    
    if (!isset($_SESSION['relatorio_comissao'])) {
        $_SESSION['mensagem'] = "Nenhum cálculo de comissão para salvar";
        header('Location: ../comissao/calcular.php');
        exit();
    }
    
    $dados = $_SESSION['relatorio_comissao'];

    foreach ($dados['resultados'] as $resultado) {
        $mes_referencia = date('Y-m-01', strtotime($dados['mes_referencia']));
        
        $sql = "INSERT INTO historico_comissao 
                (garcom_id, mes_referencia, total_vendido, valor_comissao, data_calculo) 
                VALUES (:garcom_id, :mes_referencia, :total_vendido, :valor_comissao, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':garcom_id', $resultado['id']);
        $stmt->bindParam(':mes_referencia', $mes_referencia);
        $stmt->bindParam(':total_vendido', $resultado['valor_vendas']);
        $stmt->bindParam(':valor_comissao', $resultado['comissao']);
        
        if (!$stmt->execute()) {
            $_SESSION['mensagem'] = "Erro ao salvar comissão do garçom {$resultado['nome']}";
            header('Location: ../comissao/calcular.php');
            exit();
        }
    }
    
    $_SESSION['mensagem'] = "Comissões salvas no histórico com sucesso!";
    header('Location: ../comissao/calcular.php');
    exit();
}

// Atualize a função CalcularComissao para incluir o mês de referência
function CalcularComissao() {
    $conn = getConexao();
    unset($_SESSION['comissao']);
    unset($_SESSION['relatorio_comissao']);
    
    $garcom_ids = $_POST['garcom_ids'] ?? [];
    $mes_referencia = $_POST['mes_referencia'] ?? date('Y-m');
    
    // Armazena o mês de referência na sessão para uso posterior
    $_SESSION['mes_referencia'] = $mes_referencia;
    
    if (empty($garcom_ids)) {
        $_SESSION['comissao'] = "Nenhum garçom selecionado";
        header('Location: ../comissao/calcular.php');
        exit();
    }
    
    $resultados = [];
    $total_comissao = 0;
    $total_salario = 0;
    
    foreach ($garcom_ids as $garcom_id) {
        // Busca o nome do garçom
        $sql_nome = "SELECT u.nome FROM usuario u JOIN garcom g ON u.id = g.user_id WHERE g.id = :garcom_id";
        $stmt_nome = $conn->prepare($sql_nome);
        $stmt_nome->bindParam(':garcom_id', $garcom_id);
        $stmt_nome->execute();
        $garcom_nome = $stmt_nome->fetchColumn();
        
        // Busca o valor total das contas do garçom no mês selecionado
        $sql_vendas = "SELECT SUM(valor_total) AS valor_total 
                       FROM conta 
                       WHERE garcom_id = :garcom_id 
                       AND DATE_FORMAT(data_abertura, '%Y-%m') = :mes_referencia
                       AND status = 'fechada'";
        
        $stmt_vendas = $conn->prepare($sql_vendas);
        $stmt_vendas->bindParam(':garcom_id', $garcom_id);
        $stmt_vendas->bindParam(':mes_referencia', $mes_referencia);
        $stmt_vendas->execute();
        $result = $stmt_vendas->fetch(PDO::FETCH_ASSOC);
        $valor_vendas = $result['valor_total'] ?? 0;
        
        // Calcula a comissão (10% do valor das vendas)
        $comissao = 0.1 * $valor_vendas;
        $salario_base = 1000.00; // Valor fixo do salário base
        $salario_total = $salario_base + $comissao;
        
        // Formata os valores
        $valor_vendas_formatado = number_format($valor_vendas, 2, ',', '.');
        $comissao_formatado = number_format($comissao, 2, ',', '.');
        $salario_total_formatado = number_format($salario_total, 2, ',', '.');
        
        $resultados[] = [
            'id' => $garcom_id,
            'nome' => $garcom_nome,
            'valor_vendas' => $valor_vendas,
            'valor_vendas_formatado' => $valor_vendas_formatado,
            'comissao' => $comissao,
            'comissao_formatado' => $comissao_formatado,
            'salario_base' => $salario_base,
            'salario_total' => $salario_total,
            'salario_total_formatado' => $salario_total_formatado
        ];
        
        $total_comissao += $comissao;
        $total_salario += $salario_total;
    }
    
    // Armazena na sessão para uso no relatório
    $_SESSION['relatorio_comissao'] = [
        'resultados' => $resultados,
        'total_comissao' => $total_comissao,
        'total_salario' => $total_salario,
        'data' => date('d/m/Y H:i:s'),
        'mes_referencia' => $mes_referencia
    ];
    
    // Cria mensagem para exibir na página
    
    
    // Cria mensagem de resumo
    $mensagem = "<h4>Comissões para o mês de ".date('m/Y', strtotime($mes_referencia.'-01'))."</h4>";
    $mensagem .= "<p>Cálculo realizado para " . count($garcom_ids) . " garçon(s)</p>";
    
    foreach ($resultados as $resultado) {
        $mensagem .= "<div style='margin-bottom: 15px; padding: 10px; background: #f9f9f9; border-radius: 5px;'>";
        $mensagem .= "<strong>{$resultado['nome']}:</strong><br>";
        $mensagem .= "Vendas: R$ {$resultado['valor_vendas_formatado']}<br>";
        $mensagem .= "Comissão (10%): R$ {$resultado['comissao_formatado']}<br>";
        $mensagem .= "Salário Base: R$ ".number_format($resultado['salario_base'], 2, ',', '.')."<br>";
        $mensagem .= "<strong>Total a Receber: R$ {$resultado['salario_total_formatado']}</strong>";
        $mensagem .= "</div>";
    }
    
    $mensagem .= "<div style='margin-top: 20px; padding: 10px; background: #f0f0f0; border-radius: 5px;'>";
    $mensagem .= "<strong>Total em Comissões: R$ ".number_format($total_comissao, 2, ',', '.')."</strong><br>";
    $mensagem .= "<strong>Total Geral a Pagar: R$ ".number_format($total_salario, 2, ',', '.')."</strong>";
    $mensagem .= "</div>";
    
    $_SESSION['comissao'] = $mensagem;
    
    header('Location: ../comissao/calcular.php');
    exit();
}

if (isset($_POST['salvar_comissao'])) {
    SalvarComissao();
}

if (isset($_POST['calcular_comissao'])) {
    CalcularComissao();
}

if (isset($_POST['visualizar'])) {
    VisualizarGerente();
}

    if (isset($_POST['cadastrar_gerente'])) {
    CadastrarGerente();
}
