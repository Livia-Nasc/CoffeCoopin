<?php
    require_once('conexao.php');

    function AbrirConta(){
        $conn = getConexao();
        $mesa = $_POST['mesa'];
        $garcom_id = $_POST['garcom_id'];

        $sql = "INSERT INTO conta (mesa, garcom_id, data_abertura, status)
            VALUES (:mesa, :garcom_id, NOW(), 'aberta')";
        $stmt = $conn -> prepare($sql);
        $stmt -> bindParam(':mesa', $mesa);
        $stmt -> bindParam(':garcom_id', $garcom_id);
        if($stmt -> execute()){
            echo "<script type='text/javascript'>
                        alert('Conta aberta');
                        window.location='../abrir_conta.php';
                      </script>";
        }
        else{
            echo "<script type='text/javascript'>
                        alert('Informações inexistentes');
                        window.location='../abrir_conta.php';
                      </script>";
        }
    }

    function VerConta(){
        session_start();
        $conn = getConexao();
        $sql = "SELECT id, mesa, garcom_id, data_abertura, status FROM conta";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $_SESSION['conta'] = $stmt->fetchAll();
        
        // Buscar produtos também
        $sql_produtos = "SELECT id, nome, preco FROM produto";
        $stmt_produtos = $conn->prepare($sql_produtos);
        $stmt_produtos->execute();
        $_SESSION['produtos'] = $stmt_produtos->fetchAll();
        
        header("Location: ../abrir_conta.php");
    }

    function FecharConta(){
        session_start();
        $conn = getConexao();
        $conta_id = $_POST['conta_id'];
        
        $sql = "UPDATE conta SET status = 'fechada', data_fechamento = NOW() WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $conta_id);
        
        if($stmt->execute()){
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

    function CancelarConta(){
        session_start();
        $conn = getConexao();
        $conta_id = $_POST['conta_id'];
        
        $sql = "UPDATE conta SET status = 'cancelada', data_fechamento = NOW() WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $conta_id);
        
        if($stmt->execute()){
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

    function AssociarProduto(){
        session_start();
        $conn = getConexao();
        $conta_id = $_POST['conta_id'];
        $produto_id = $_POST['produto_id'];
        $quantidade = $_POST['quantidade'];
        
        // Verificar estoque primeiro
        $sql_estoque = "SELECT qtd_estoque FROM produto WHERE id = :produto_id";
        $stmt_estoque = $conn->prepare($sql_estoque);
        $stmt_estoque->bindParam(':produto_id', $produto_id);
        $stmt_estoque->execute();
        $estoque = $stmt_estoque->fetchColumn();
        
        if($estoque < $quantidade) {
            echo "<script type='text/javascript'>
                    alert('Quantidade indisponível em estoque');
                    window.location='../abrir_conta.php';
                  </script>";
            return;
        }
        
        // Primeiro verifica se o produto já está associado à conta
        $sql_check = "SELECT * FROM pedido WHERE conta_id = :conta_id AND produto_id = :produto_id";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':conta_id', $conta_id);
        $stmt_check->bindParam(':produto_id', $produto_id);
        $stmt_check->execute();
        
        if($stmt_check->rowCount() > 0){
            // Se já existe, atualiza a quantidade
            $sql_update = "UPDATE pedido SET quantidade = quantidade + :quantidade 
                          WHERE conta_id = :conta_id AND produto_id = :produto_id";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bindParam(':quantidade', $quantidade);
            $stmt_update->bindParam(':conta_id', $conta_id);
            $stmt_update->bindParam(':produto_id', $produto_id);
            $result = $stmt_update->execute();
        } else {
            // Se não existe, insere novo registro
            $sql_insert = "INSERT INTO pedido (conta_id, produto_id, quantidade) 
                          VALUES (:conta_id, :produto_id, :quantidade)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bindParam(':conta_id', $conta_id);
            $stmt_insert->bindParam(':produto_id', $produto_id);
            $stmt_insert->bindParam(':quantidade', $quantidade);
            $result = $stmt_insert->execute();
        }
        
        // Atualizar estoque
        if($result) {
            $sql_update_estoque = "UPDATE produto SET qtd_estoque = qtd_estoque - :quantidade WHERE id = :produto_id";
            $stmt_update_estoque = $conn->prepare($sql_update_estoque);
            $stmt_update_estoque->bindParam(':quantidade', $quantidade);
            $stmt_update_estoque->bindParam(':produto_id', $produto_id);
            $stmt_update_estoque->execute();
        }
        
        if($result){
            echo "<script type='text/javascript'>
                        alert('Produto associado com sucesso');
                        window.location='../abrir_conta.php';
                      </script>";
        } else {
            echo "<script type='text/javascript'>
                        alert('Erro ao associar produto');
                        window.location='../abrir_conta.php';
                      </script>";
        }
    }

    function ExcluirPedido() {
        session_start();
        $conn = getConexao();
        $pedido_id = $_POST['pedido_id'];
        
        $sql_select = "SELECT produto_id, quantidade FROM pedido WHERE id = :id";
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->bindParam(':id', $pedido_id);
        $stmt_select->execute();
        $pedido = $stmt_select->fetch();
        
        if ($pedido) {
            $sql_estoque = "UPDATE produto SET qtd_estoque = qtd_estoque + :quantidade WHERE id = :produto_id";
            $stmt_estoque = $conn->prepare($sql_estoque);
            $stmt_estoque->bindParam(':quantidade', $pedido['quantidade']);
            $stmt_estoque->bindParam(':produto_id', $pedido['produto_id']);
            $stmt_estoque->execute();
          
            $sql_delete = "DELETE FROM pedido WHERE id = :id";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bindParam(':id', $pedido_id);
            
            if($stmt_delete->execute()){
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
    
    if(isset($_POST['excluir_pedido'])){
        ExcluirPedido();
    }

    if(isset($_POST['abrir_conta'])){
        AbrirConta();
    }
    if(isset($_POST['visualizar'])){
        VerConta();
    }
    if(isset($_POST['fechar_conta'])){
        FecharConta();
    }
    if(isset($_POST['cancelar_conta'])){
        CancelarConta();
    }
    if(isset($_POST['associar_produto'])){
        AssociarProduto();
    }
?>