<?php
    require_once('conexao.php');
    session_start();

    function CadastrarProduto(){
        $conn = getConexao();

        $nome = strtupper($_POST['nome']);
        $preco = $_POST['preco'];
        $categoria = filter_var($_POST['categoria']);
        $porcao = filter_var($_POST['porcao']);
        $qtd_estoque = $_POST['qtd_estoque'];
        if ($categoria){
            if ($porcao){
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
            else{
            echo "<script type='text/javascript'>
                        alert('Escolha não selecionada');
                        window.location='../cadastro_produto.php';
                      </script>";
        }

        }
        else{
            echo "<script type='text/javascript'>
                        alert('Escolha não selecionada');
                        window.location='../cadastro_produto.php';
                      </script>";
        }


    }

    function VisualizarProduto() {
    session_start();
    if(isset($_SESSION['produto'])){
        unset($_SESSION['produto']);
        $conn = getConexao();
        $nome = $_POST['nome'] ?? '';

        if ($nome != '') {
            $sql = "SELECT * FROM produto WHERE nome LIKE :nome";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':nome', "%$nome%");
            $stmt->execute();

            if ($stmt->rowCount() >= 1) {
                $result = $stmt->fetchAll();
                $_SESSION['produto'] = [];

                foreach ($result as $produto) {
                    $_SESSION['produto'][] = [
                        'nome' => $produto['nome'],
                        'preco' => $produto['preco'],
                        'categoria' => $produto['categoria'],
                        'porcao' => $produto['porcao'],
                        'qtd_estoque' => $produto['qtd_estoque']
                    ];
                }
                header("Location: ../cadastro_produto.php");
            } else {
                echo "<script type='text/javascript'>
                        alert('Erro ao encontrar produto');
                        window.location='../cadastro_produto.php';
                    </script>";
                exit();
            }
        } else {
            $sql = "SELECT * FROM produto";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            $_SESSION['produto'] = $result;
            header("Location: ../cadastro_produto.php");
            exit();
        }
    }

}

    function ExcluirProduto(){
        $conn = getConexao();
        $nome = $_POST['nome'];
        $sql = "DELETE FROM produto WHERE nome = :nome";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        if ($stmt->execute()){
            echo "<script type='text/javascript'>
            alert('Produto excluído com sucesso!');
            window.location='../cadastro_produto.php';
            </script>";
            }
        else{
            echo "Erro ao excluir produto!";
        }
    }

    function AlterarProduto(){
        $conn = getConexao();
        $nome = $_POST['nome'];
        $preco = $_POST['preco'];
        $categoria = $_POST['categoria'];
        $porcao = $_POST['porcao'];
        $qtd_estoque = $_POST['qtd_estoque'];
        $sql = "UPDATE produto SET nome = :nome, preco = :preco, categoria = :categoria, porcao = :porcao, qtd_estoque = :qtd_estoque WHERE nome = :nome";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':porcao', $porcao);
        $stmt->bindParam(':qtd_estoque', $qtd_estoque);
    }

    if(isset($_POST['cadastrar'])){
        CadastrarProduto();
    }

    if(isset($_POST['visualizar'])){
        VisualizarProduto();
    }

    if(isset($_POST['excluir'])){
        ExcluirProduto();
    }

?>