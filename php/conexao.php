<?php

//  Criar conexão com o banco de dados

function getConexao()
{
    $dsn = 'mysql:host=localhost;dbname=coopin;charset=utf8';
    // ? Tipo do banco, local do banco e nome do banco
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO($dsn, $user, $pass);
        return $pdo;
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Erro genérico!" . $e->getMessage();
    }
}
getConexao();
