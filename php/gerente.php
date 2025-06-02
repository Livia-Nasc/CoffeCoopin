<?php
require_once("conexao.php");
require("usuario.php");

function CadastrarGerente()
{
    $conn = getConexao();

    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $data_nasc = $_POST['data_nasc'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $cpf = $_POST['cpf'];
    $rg = $_POST['rg'];

    $sql = 'SELECT * FROM usuario WHERE cpf = :cpf OR email = :email'; // ! Seleciona dados usando o CPF e o email do usuário a ser cadastrado
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':cpf', $cpf);
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
        $stmt->bindParam(':cpf', $cpf);

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
                        window.location='../cadastro/cadastro.php';
                      </script>";
    }
}

function CalcularComissao() {
    $conn = getConexao();
    session_start();
    unset($_SESSION['comissao']);
    $garcom_ids = $_POST['garcom_ids'] ?? [];
    
    // Verifica se há garçons selecionados
    if (empty($garcom_ids)) {
        $_SESSION['comissao'] = "Nenhum garçom selecionado";
        header('Location: ../calcular_comissao.php');
        exit();
    }
    
    // Array para armazenar os resultados de cada garçom
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
        
        // Busca o valor total das contas do garçom
        $sql_vendas = "SELECT SUM(valor_total) AS valor_total FROM conta WHERE garcom_id = :garcom_id";
        $stmt_vendas = $conn->prepare($sql_vendas);
        $stmt_vendas->bindParam(':garcom_id', $garcom_id);
        $stmt_vendas->execute();
        $result = $stmt_vendas->fetch(PDO::FETCH_ASSOC);
        $valor_vendas = $result['valor_total'] ?? 0;
        
        // Calcula a comissão (10% do valor das vendas)
        $comissao = 0.1 * $valor_vendas;
        $salario_base = 1000.00;
        $salario_total = $salario_base + $comissao;
        
        // Formata os valores
        $valor_vendas_formatado = number_format($valor_vendas, 2, ',', '.');
        $comissao_formatado = number_format($comissao, 2, ',', '.');
        $salario_total_formatado = number_format($salario_total, 2, ',', '.');
        
        // Armazena os resultados
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
        'data' => date('d/m/Y H:i:s')
    ];
    
    // Cria mensagem de resumo para exibir na página
    $mensagem = "Cálculo realizado para " . count($garcom_ids) . " garçon(s)<br>";
    foreach ($resultados as $resultado) {
        $mensagem .= "<br><strong>{$resultado['nome']}:</strong><br>"
                   . "Vendas: R$ {$resultado['valor_vendas_formatado']}<br>"
                   . "Comissão: R$ {$resultado['comissao_formatado']}<br>"
                   . "Total: R$ {$resultado['salario_total_formatado']}<br>";
    }
    $mensagem .= "<br><strong>Total geral:</strong> R$ " . number_format($total_salario, 2, ',', '.');
    
    $_SESSION['comissao'] = $mensagem;
    header('Location: ../calcular_comissao.php');
    exit();
}

if (isset($_POST['calcular_comissao'])) {
    CalcularComissao();
}

    if (isset($_POST['cadastrar_gerente'])) {
    CadastrarGerente();
}
