<?php
    $cpf = '555.555.555-55';
    $cpf_novo = preg_replace('/[^0-9]/', '', $cpf);
    echo $cpf_novo;
?>