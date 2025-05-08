<?php
require_once('conexao.php');

function CadastrarGarcom()
{
    $conn = getConexao();

    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $data_nasc = $_POST['data_nasc'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $cpf = $_POST['cpf'];
    $escolaridade = $_POST['escolaridade'];

    $sql = 'SELECT * FROM usuario WHERE cpf = :cpf OR email = :email'; // ! Seleciona dados usando o CPF e o email do usuário a ser cadastrado
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();

    if ($stmt->rowcount() == 0) { // ! Verifica se o CPF e o e-mail não estão registrados

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $cadastro = 'INSERT INTO usuario(nome, telefone, data_nasc, email, senha, cpf, tipo) VALUES( :nome, :telefone, :data_nasc,:email, :senha, :cpf, 3)'; // ! Insere dados passados no formulário contido na página cadastro.php na tabela usuario
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
            $cadastroGarcom = 'INSERT INTO garcom(escolaridade, user_id) VALUES(:escolaridade, :user_id)';
            $stmt = $conn->prepare($cadastroGarcom);
            $stmt->bindParam(':escolaridade', $escolaridade);
            $stmt->bindParam(':user_id', $user_id);
            if ($stmt->execute()) {
                header('location: ../login.php'); // ! Vai para a página de login
                exit();
            } else {
                echo "Erro ao cadastrar usuário.";
            }
        }
    } else {
        echo "<script type='text/javascript'>
                    alert('Informações já existentes');  // ! Se o CPF e o e-mail já existirem, exibe mensagem de erro na página cadastro.php
                    window.location='../cadastro.php';
                  </script>";
    }
}

// Em php/garcom.php
function CalcularComissao() {
    // $conn = getConexao();
    // $mes = $_POST['mes'];
    
    // $sql = "SELECT g.id, u.nome, 
    //                SUM(p.preco * pd.quantidade) as total_vendido,
    //                SUM(p.preco * pd.quantidade) * 0.1 as comissao
    //         FROM garcom g
    //         JOIN usuario u ON g.user_id = u.id
    //         JOIN conta c ON c.garcom_id = g.id
    //         JOIN pedido pd ON c.id = pd.conta_id
    //         JOIN produto p ON pd.produto_id = p.id
    //         WHERE MONTH(c.data_fechamento) = :mes
    //         AND c.status = 'fechada'
    //         GROUP BY g.id, u.nome";
    
    // $stmt = $conn->prepare($sql);
    // $stmt->bindParam(':mes', $mes);
    // $stmt->execute();
    
    
    // // $_SESSION['relatorio_comissoes'] = $stmt->fetchAll();
    // header("Location: ../relatorio_comissoes.php");
    $salario = 1000.00;
    $comissao_salario = $salario + 12;
    echo number_format($comissao_salario, 2, ',', '.');
}

if (isset($_POST['cadastrar_garcom'])) {
    CadastrarGarcom();
}
if (isset($_POST['calcular_comissao'])) {
    CalcularComissao();
}
?>