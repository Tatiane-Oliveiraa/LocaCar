<?php
include('../database/conexao.php');

$id_cliente = $_POST["id_cliente"];
$sql = "DELETE FROM clientes WHERE id_cliente = $id_cliente";

if ($conexao->query($sql) === TRUE) {
    echo "<script>
        alert('Cliente excluído com sucesso!');
        window.location.href = 'index.html';
    </script>";
} else {
    echo "<script>
        alert('Erro ao excluir cliente: " . addslashes($conexao->error) . "');
        window.location.href = 'clientes.php';
    </script>";
}
?>