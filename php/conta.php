<?php
require_once('conexao.php');

function VerConta()
{
    session_start();
    $conn = getConexao();

    $user_id = $_SESSION['usuario']['id'];
    $sql_garcom = "SELECT id FROM garcom WHERE user_id = :user_id";
    $stmt_garcom = $conn->prepare($sql_garcom);
    $stmt_garcom->bindParam(':user_id',$user_id);
    $stmt_garcom->execute();
    $garcom_id = $stmt_garcom->fetchAll();

    $sql = "SELECT id, mesa, garcom_id, data_abertura, status FROM conta WHERE garcom_id = :garcom_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':garcom_id', $garcom_id);
    $stmt->execute();
    $_SESSION['conta'] = $stmt->fetchAll();

    // ! Busca produtos também 
    $sql_produtos = "SELECT id, nome, preco FROM produto";
    $stmt_produtos = $conn->prepare($sql_produtos);
    $stmt_produtos->execute();
    $_SESSION['produtos'] = $stmt_produtos->fetchAll();

    header("Location: ../abrir_conta.php");
}

function AbrirConta()
{
    $conn = getConexao();
    $mesa = $_POST['mesa'];
    $garcom_id = $_POST['garcom_id'];
    // ! Abre uma conta nova
    $sql = "INSERT INTO conta (mesa, garcom_id, data_abertura, status)
            VALUES (:mesa, :garcom_id, NOW(), 'aberta')";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':mesa', $mesa);
    $stmt->bindParam(':garcom_id', $garcom_id);
    if ($stmt->execute()) {
        VerConta();
        echo "<script type='text/javascript'>
                        alert('Conta aberta');
                        window.location='../abrir_conta.php';
                      </script>";
                      
    } else {
        echo "<script type='text/javascript'>
                        alert('Informações inexistentes');
                        window.location='../abrir_conta.php';
                      </script>";
    }

}

function FecharConta()
{
    session_start();
    $conn = getConexao();
    $conta_id = $_POST['conta_id'];
    // ! Muda o status da conta de "aberta" para "fechada"
    $sql = "UPDATE conta SET status = 'fechada', data_fechamento = NOW() WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $conta_id);

    if ($stmt->execute()) {
        echo "<script type='text/javascript'>
                        alert('Conta fechada com sucesso');
                        window.location='../abrir_conta.php';
                      </script>";
    } else {
        echo "<script type='text/javascript'>
                        alert('Erro ao fechar conta');
                        window.location='../abrir_conta.php';
                      </script>";
    }
}

function CancelarConta()
{
    session_start();
    $conn = getConexao();
    $conta_id = $_POST['conta_id'];
    // ! Muda o status da conta de "aberta" para "cancelada"
    $sql = "UPDATE conta SET status = 'cancelada', data_fechamento = NOW() WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $conta_id);

    if ($stmt->execute()) {
        echo "<script type='text/javascript'>
                        alert('Conta cancelada com sucesso');
                        window.location='../abrir_conta.php';
                      </script>";
    } else {
        echo "<script type='text/javascript'>
                        alert('Erro ao cancelar conta');
                        window.location='../abrir_conta.php';
                      </script>";
    }
}

function AssociarProduto()
{
    session_start();
    $conn = getConexao();
    $conta_id = $_POST['conta_id'];
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];

    // ! Verificar estoque primeiro
    $sql_estoque = "SELECT qtd_estoque, preco FROM produto WHERE id = :produto_id";
    $stmt_estoque = $conn->prepare($sql_estoque);
    $stmt_estoque->bindParam(':produto_id', $produto_id);
    $stmt_estoque->execute();
    $produto = $stmt_estoque->fetch();
    $estoque = $produto['qtd_estoque'];
    $preco_unitario = $produto['preco'];

    if ($estoque < $quantidade) {
        echo "<script type='text/javascript'>
                    alert('Quantidade indisponível em estoque');
                    window.location='../abrir_conta.php';
                  </script>";
        return;
    }

    // ! Primeiro verifica se o produto já está associado à conta
    $sql_check = "SELECT * FROM pedido WHERE conta_id = :conta_id AND produto_id = :produto_id";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(':conta_id', $conta_id);
    $stmt_check->bindParam(':produto_id', $produto_id);
    $stmt_check->execute();
    
    $result = false;
    $valor_adicionado = $preco_unitario * $quantidade;
    
    if ($stmt_check->rowCount() > 0) {
        // ! Se já existe, atualiza a quantidade
        $sql_update = "UPDATE pedido SET quantidade = quantidade + :quantidade 
                          WHERE conta_id = :conta_id AND produto_id = :produto_id";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bindParam(':quantidade', $quantidade);
        $stmt_update->bindParam(':conta_id', $conta_id);
        $stmt_update->bindParam(':produto_id', $produto_id);
        $result = $stmt_update->execute();
    } else {
        // ! Se não existe, insere novo registro
        $sql_insert = "INSERT INTO pedido (conta_id, produto_id, quantidade) 
                          VALUES (:conta_id, :produto_id, :quantidade)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindParam(':conta_id', $conta_id);
        $stmt_insert->bindParam(':produto_id', $produto_id);
        $stmt_insert->bindParam(':quantidade', $quantidade);
        $result = $stmt_insert->execute();
    }

    // ! Atualizar estoque e valor total da conta
    if ($result) {
        // Atualizar estoque
        $sql_update_estoque = "UPDATE produto SET qtd_estoque = qtd_estoque - :quantidade WHERE id = :produto_id";
        $stmt_update_estoque = $conn->prepare($sql_update_estoque);
        $stmt_update_estoque->bindParam(':quantidade', $quantidade);
        $stmt_update_estoque->bindParam(':produto_id', $produto_id);
        $stmt_update_estoque->execute();

        // Atualizar valor total da conta
        $sql_update_conta = "UPDATE conta SET valor_total = valor_total + :valor_adicionado WHERE id = :conta_id";
        $stmt_update_conta = $conn->prepare($sql_update_conta);
        $stmt_update_conta->bindParam(':valor_adicionado', $valor_adicionado);
        $stmt_update_conta->bindParam(':conta_id', $conta_id);
        $stmt_update_conta->execute();
    }

    if ($result) {
        echo "<script type='text/javascript'>
                        alert('Produto associado com sucesso');
                        window.location='../visualizar/conta.php';
                      </script>";
    } else {
        echo "<script type='text/javascript'>
                        alert('Erro ao associar produto');
                        window.location='../visualizar/conta.php';
                      </script>";
    }
}

function ExcluirPedido()
{
    session_start();
    $conn = getConexao();
    $pedido_id = $_POST['pedido_id'];

    $sql_select = "SELECT pd.produto_id, pd.quantidade, p.preco 
                   FROM pedido pd
                   JOIN produto p ON pd.produto_id = p.id
                   WHERE pd.id = :id";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bindParam(':id', $pedido_id);
    $stmt_select->execute();
    $pedido = $stmt_select->fetch();

    if ($pedido) {
        // ! Se o pedido existir ele vai ser exluído
        $valor_removido = $pedido['preco'] * $pedido['quantidade'];
        
        // Primeiro atualiza o estoque
        $sql_estoque = "UPDATE produto SET qtd_estoque = qtd_estoque + :quantidade WHERE id = :produto_id";
        $stmt_estoque = $conn->prepare($sql_estoque);
        $stmt_estoque->bindParam(':quantidade', $pedido['quantidade']);
        $stmt_estoque->bindParam(':produto_id', $pedido['produto_id']);
        $stmt_estoque->execute();

        // Depois atualiza o valor total da conta
        $sql_update_conta = "UPDATE conta SET valor_total = valor_total - :valor_removido 
                             WHERE id = (SELECT conta_id FROM pedido WHERE id = :pedido_id)";
        $stmt_update_conta = $conn->prepare($sql_update_conta);
        $stmt_update_conta->bindParam(':valor_removido', $valor_removido);
        $stmt_update_conta->bindParam(':pedido_id', $pedido_id);
        $stmt_update_conta->execute();

        // Finalmente exclui o pedido
        $sql_delete = "DELETE FROM pedido WHERE id = :id";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bindParam(':id', $pedido_id);

        if ($stmt_delete->execute()) {
            echo "<script type='text/javascript'>
                        alert('Pedido excluído com sucesso');
                        window.location='../abrir_conta.php';
                      </script>";
        } else {
            echo "<script type='text/javascript'>
                        alert('Erro ao excluir pedido');
                        window.location='../abrir_conta.php';
                      </script>";
        }
    } else {
        echo "<script type='text/javascript'>
                    alert('Pedido não encontrado');
                    window.location='../abrir_conta.php';
                  </script>";
    }
}

// Em php/conta.php
function GerarRelatorioOcupacao() {
    $conn = getConexao();
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    
    $sql = "SELECT c.mesa, SUM(p.preco * pd.quantidade) as total_vendido
            FROM conta c
            JOIN pedido pd ON c.id = pd.conta_id
            JOIN produto p ON pd.produto_id = p.id
            WHERE c.data_abertura BETWEEN :data_inicio AND :data_fim
            GROUP BY c.mesa";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':data_inicio', $data_inicio);
    $stmt->bindParam(':data_fim', $data_fim);
    $stmt->execute();
    
    $_SESSION['relatorio_mesas'] = $stmt->fetchAll();
    header("Location: ../relatorio_mesas.php");
}

if (isset($_POST['excluir_pedido'])) {
    ExcluirPedido();
}

if (isset($_POST['abrir_conta'])) {
    AbrirConta();
}
if (isset($_POST['visualizar'])) {
    VerConta();
}
if (isset($_POST['fechar_conta'])) {
    FecharConta();
}
if (isset($_POST['cancelar_conta'])) {
    CancelarConta();
}
if (isset($_POST['associar_produto'])) {
    AssociarProduto();
}
