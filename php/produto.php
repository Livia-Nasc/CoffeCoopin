<?php
    require_once('conexao.php');
    session_start();

    Function CadastrarProduto(){
        $conn = getConexao();

        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $categoria = $_POST['categoria'];
        $porcao = $_POST['porcao'];
        $qtd_estoque = $_POST['qtd_estoque'];

        $sql = "INSERT INTO produto (nome, preco, categoria, porcao, qtd_estoque) values ( :nome, :preco, :categoria, :porcao, :qtd_estoque)";
        $stmt = $conn -> prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':porcao', $porcao);
        $stmt->bindParam(':qtd_estoque', $qtd_estoque);
        if ($stmt->execute()){
            echo "Produto cadastrado com sucesso!";
            header('location:../cadastro_produto.php');
        }
        else{
            echo "Erro ao cadastrar produto!";
        }
    }
    if(isset($_POST['cadastrar'])){
        CadastrarProduto();
    }
    
    Function VisualizarProduto(){
        $conn = getConexao();
        $sql = "SELECT * FROM produto";
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute()){
            $result = $stmt->fetchAll();
            $_SESSION['nome'] = [];
            $_SESSION['preco'] = [];
            $_SESSION['categoria'] = [];
            $_SESSION['porcao'] = [];
            $_SESSION['qtd_estoque'] = [];
                foreach ($result as $value) {
                    $_SESSION['preco'][] = $value['preco'];
                    $_SESSION['nome'][] = $value['nome'];
                    $_SESSION['categoria'][] = $value['categoria'];
                    $_SESSION['porcao'][] = $value['porcao'];
                    $_SESSION['qtd_estoque'][] = $value['qtd_estoque'];
                }
                header("Location: ../cadastro_produto.php");
        }
        else{
            echo "Erro ao visualizar produtos!";
        }
    }
    
    if(isset($_POST['visualizar'])){
        VisualizarProduto();
    }

?>