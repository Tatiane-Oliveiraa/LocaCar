<?php
include('../database/conexao.php');

$id_carro = $_POST["id_carro"];
$sql = "DELETE FROM carros WHERE id_carro = $id_carro";

if ($conexao->query($sql) === TRUE) {
    echo "<script>
        alert('Carro excluído com sucesso!');
        window.location.href = 'carros.html';
    </script>";
} else {
    echo "<script>
        alert('Erro ao excluir carro: " . addslashes($conexao->error) . "');
        window.location.href = 'carros.php';
    </script>";
}
?>