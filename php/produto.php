<?php
    // require_once('conexao.php');
    // session_start();

    // Function CadastrarProduto(){
    //     $conn = getConexao();

    //     $nome = $_POST['nome'];
    //     $preco = $_POST['preco'];
    //     $categoria = filter_var($_POST['categoria']);
    //     $porcao = filter_var($_POST['porcao']);
    //     $qtd_estoque = $_POST['qtd_estoque'];
    //     if ($categoria){
    //         if ($porcao){
    //             $sql = "INSERT INTO produto (nome, preco, categoria, porcao, qtd_estoque) values ( :nome, :preco, :categoria, :porcao, :qtd_estoque)";
    //             $stmt = $conn -> prepare($sql);
    //             $stmt->bindParam(':nome', $nome);
    //             $stmt->bindParam(':preco', $preco);
    //             $stmt->bindParam(':categoria', $categoria);
    //             $stmt->bindParam(':porcao', $porcao);
    //             $stmt->bindParam(':qtd_estoque', $qtd_estoque);
    //             if ($stmt->execute()){
    //                 echo "Produto cadastrado com sucesso!";
    //                 header('location:../cadastro_produto.php');
    //             }
    //             else{
    //                 echo "Erro ao cadastrar produto!";
    //             }
    //         }
    //         else{
    //         echo "<script type='text/javascript'>
    //                     alert('Escolha não selecionada');
    //                     window.location='../cadastro_produto.php';
    //                   </script>";
    //     }

    //     }
    //     else{
    //         echo "<script type='text/javascript'>
    //                     alert('Escolha não selecionada');
    //                     window.location='../cadastro_produto.php';
    //                   </script>";
    //     }


    // }

    // Function VisualizarProduto(){
    //     $conn = getConexao();
    //     $sql = "SELECT * FROM produto";
    //     $stmt = $conn->prepare($sql);

    //     if ($stmt->execute()){
    //         session_destroy();
    //         // session_unset($_SESSION['nome']);
    //         // session_unset($_SESSION['preco']);
    //         // session_unset($_SESSION['categoria']);
    //         // session_unset($_SESSION['porcao']);
    //         // session_unset($_SESSION['qtd_estoque']);
    //         session_start();
    //         $result = $stmt->fetchAll();
    //         $_SESSION['nome'] = [];
    //         $_SESSION['preco'] = [];
    //         $_SESSION['categoria'] = [];
    //         $_SESSION['porcao'] = [];
    //         $_SESSION['qtd_estoque'] = [];
    //             foreach ($result as $value) {
    //                 $_SESSION['preco'][] = $value['preco'];
    //                 $_SESSION['nome'][] = $value['nome'];
    //                 $_SESSION['categoria'][] = $value['categoria'];
    //                 $_SESSION['porcao'][] = $value['porcao'];
    //                 $_SESSION['qtd_estoque'][] = $value['qtd_estoque'];
    //             }
    //             header("Location: ../cadastro_produto.php");
    //     }
    //     else{
    //         echo "Erro ao visualizar produtos!";
    //     }
    // }

    // function ExcluirProduto(){
    //     $conn = getConexao();
    //     $nome = $_POST['nome'];
    //     $sql = "DELETE FROM produto WHERE nome = :nome";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bindParam(':nome', $nome);
    //     if ($stmt->execute()){
    //         echo "<script type='text/javascript'>
    //         alert('Produto excluído com sucesso!');
    //         window.location='../cadastro_produto.php';
    //         </script>";
    //         }
    //     else{
    //         echo "Erro ao excluir produto!";
    //     }
    // }

    // function AlterarProduto(){
    //     $conn = getConexao();
    //     $nome = $_POST['nome'];
    //     $preco = $_POST['preco'];
    //     $categoria = $_POST['categoria'];
    //     $porcao = $_POST['porcao'];
    //     $qtd_estoque = $_POST['qtd_estoque'];
    //     $sql = "UPDATE produto SET nome = :nome, preco = :preco, categoria = :categoria, porcao = :porcao, qtd_estoque = :qtd_estoque WHERE nome = :nome";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bindParam(':nome', $nome);
    //     $stmt->bindParam(':preco', $preco);
    //     $stmt->bindParam(':categoria', $categoria);
    //     $stmt->bindParam(':porcao', $porcao);
    //     $stmt->bindParam(':qtd_estoque', $qtd_estoque);
        
        
    // }

    // if(isset($_POST['cadastrar'])){
    //     CadastrarProduto();
    // }

    // if(isset($_POST['visualizar'])){
    //     VisualizarProduto();
    // }

    // if(isset($_POST['excluir'])){
    //     ExcluirProduto();
    // }

// require_once('conexao.php');
// session_start();

// // Constantes para categorias
// define('CATEGORIAS', ['Bebidas', 'Sobremesas', 'Salgados', 'Cafés Especiais']);
// define('PORCOES', ['Pequena', 'Média', 'Grande']);

// function CadastrarProduto() {
//     $conn = getConexao();

//     // Validação
//     $nome = $_POST['nome'];
//     $preco = $_POST['preco'];
//     $categoria = filter_var($_POST['categoria']);
//     $porcao = filter_var($_POST['porcao']);
//     $qtd_estoque = $_POST['qtd_estoque'];

//     if (!$nome || !$preco || !$categoria || !$porcao || !$qtd_estoque) {
//         $_SESSION['erro'] = "Todos os campos são obrigatórios!";
//         header('Location: ../cadastro_produto.php');
//         exit();
//     }

//     if (!in_array($categoria, CATEGORIAS)) {
//         $_SESSION['erro'] = "Categoria inválida!";
//         header('Location: ../cadastro_produto.php');
//         exit();
//     }

//     if (!in_array($porcao, PORCOES)) {
//         $_SESSION['erro'] = "Porção inválida!";
//         header('Location: ../cadastro_produto.php');
//         exit();
//     }

//     try {
//         $sql = "INSERT INTO produto (nome, preco, categoria, porcao, qtd_estoque) 
//                 VALUES (:nome, :preco, :categoria, :porcao, :qtd_estoque)";
//         $stmt = $conn->prepare($sql);
//         $stmt->bindParam(':nome', $nome);
//         $stmt->bindParam(':preco', $preco);
//         $stmt->bindParam(':categoria', $categoria);
//         $stmt->bindParam(':porcao', $porcao);
//         $stmt->bindParam(':qtd_estoque', $qtd_estoque);

//         if ($stmt->execute()) {
//             $_SESSION['sucesso'] = "Produto cadastrado com sucesso!";
//         } else {
//             $_SESSION['erro'] = "Erro ao cadastrar produto!";
//         }
//     } catch (PDOException $e) {
//         $_SESSION['erro'] = "Erro no banco de dados: " . $e->getMessage();
//     }

//     header('Location: ../cadastro_produto.php');
//     exit();
// }

// function VisualizarProdutos() {
//     $conn = getConexao();

//     try {
//         $sql = "SELECT * FROM produto ORDER BY nome";
//         $stmt = $conn->prepare($sql);

//         if ($stmt->execute()) {
//             $_SESSION['produtos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         } else {
//             $_SESSION['erro'] = "Erro ao buscar produtos!";
//         }
//     } catch (PDOException $e) {
//         $_SESSION['erro'] = "Erro no banco de dados: " . $e->getMessage();
//     }

//     header("Location: ../cadastro_produto.php");
//     exit();
// }

// function ExcluirProduto() {
//     $conn = getConexao();
//     $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

//     if (!$id) {
//         $_SESSION['erro'] = "ID do produto inválido!";
//         header('Location: ../cadastro_produto.php');
//         exit();
//     }

//     try {
//         $sql = "DELETE FROM produto WHERE cod_produto = :id";
//         $stmt = $conn->prepare($sql);
//         $stmt->bindParam(':id', $id);

//         if ($stmt->execute()) {
//             $_SESSION['sucesso'] = "Produto excluído com sucesso!";
//         } else {
//             $_SESSION['erro'] = "Erro ao excluir produto!";
//         }
//     } catch (PDOException $e) {
//         $_SESSION['erro'] = "Erro no banco de dados: " . $e->getMessage();
//     }

//     header('Location: ../cadastro_produto.php');
//     exit();
// }

// // Roteamento
// $acao = isset($_POST['acao']) ? $_POST['acao'] : '';

// switch ($acao) {
//     case 'cadastrar':
//         CadastrarProduto();
//         break;
//     case 'visualizar':
//         VisualizarProdutos();
//         break;
//     case 'excluir':
//         ExcluirProduto();
//         break;
//     default:
//         $_SESSION['erro'] = "Ação inválida!";
//         header('Location: ../cadastro_produto.php');
//         exit();
// }

require_once('conexao.php');
session_start();

// Cadastrar produto
if(isset($_POST['cadastrar'])) {
    $conn = getConexao();
    
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];
    $porcao = $_POST['porcao'];
    $qtd_estoque = $_POST['qtd_estoque'];
    
    $sql = "INSERT INTO produto (nome, preco, categoria, porcao, qtd_estoque) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$nome, $preco, $categoria, $porcao, $qtd_estoque])) {
        header('Location: cadastro_produto.php?sucesso=Produto cadastrado!');
    } else {
        header('Location: cadastro_produto.php?erro=Erro ao cadastrar');
    }
    exit();
}

// Visualizar produtos
if(isset($_POST['visualizar'])) {
    $conn = getConexao();
    
    $sql = "SELECT * FROM produto";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $_SESSION['produtos'] = $stmt->fetchAll();
    
    header('Location: cadastro_produto.php');
    exit();
}

// Excluir produto
if(isset($_POST['excluir'])) {
    $conn = getConexao();
    
    $nome = $_POST['nome'];
    
    $sql = "DELETE FROM produto WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    
    if($stmt->execute([$nome])) {
        header('Location: cadastro_produto.php?sucesso=Produto excluído!');
    } else {
        header('Location: cadastro_produto.php?erro=Erro ao excluir');
    }
    exit();
}

?>