<?php

    $servidor = 'localhost';
    $usuario = 'root';
    $senha = '5560';
    $banco = 'locacar';

    $conexao = new mysqli($servidor, $usuario, $senha, $banco);

    if($conexao->connect_error){
        die("Erro na conexão: " . $conexao->connect_error);
    }

?>