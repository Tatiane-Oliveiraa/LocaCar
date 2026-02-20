<?php
include('../database/conexao.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$status = null;
$resultado = null;
$carros = [];
$historicos = [];

// Processa a busca se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placa = $_POST['placa'];

    if (empty($placa)) {
        $status = 'vazio';
    } else {
        try {
            // Consulta carros pela placa
            $sql = "SELECT * FROM carros WHERE placa LIKE ?";
            $stmt = $conexao->prepare($sql);
            $likePlaca = "%$placa%";
            $stmt->bind_param("s", $likePlaca);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows === 0) {
                $status = 'nao_encontrado';
            } else {
                $status = 'encontrado';

                while ($carro = $resultado->fetch_assoc()) {
                    $id_carro = $carro['id_carro'];
                    $carros[$id_carro] = $carro;

                    // Consulta histórico de aluguéis para o carro
                    $stmtHistorico = $conexao->prepare("
                        SELECT a.dt_aluguel, a.dt_devolucao, c.nome, c.cpf
                        FROM alugar a
                        JOIN clientes c ON a.id_cliente = c.id_cliente
                        WHERE a.id_carro = ?
                    ");
                    $stmtHistorico->bind_param("i", $id_carro);
                    $stmtHistorico->execute();
                    $historicos[$id_carro] = $stmtHistorico->get_result();
                }
            }
        } catch (Exception $e) {
            $status = 'erro';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consultar Carros</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .status-ativo { color: green; font-weight: bold; }
        .status-finalizado { color: gray; font-weight: bold; }
        table { border-collapse: collapse; margin-top: 20px; width: 100%; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        th { background-color: yellowgreen; color: white; }
    </style>
</head>
<body>
    <main>
        <h2>🔍 Consultar Carros</h2>

        <!-- Formulário de busca -->
        <form method="POST">
            <label for="placa">Digite a Placa:</label><br>
            <input type="text" name="placa" id="placa" required>
            <br><br>
            <button type="submit">Consultar</button>
        </form>

        <!-- Mensagens de status -->
        <?php if ($status === 'vazio'): ?>
            <div class="mensagem">⚠️ Campo de busca vazio. Por favor, insira uma placa.</div>
        <?php elseif ($status === 'nao_encontrado'): ?>
            <div class="mensagem">❌ Nenhum carro encontrado com essa placa.</div>
        <?php elseif ($status === 'erro'): ?>
            <div class="mensagem">❌ Erro ao consultar. Tente novamente.</div>
        <?php endif; ?>

        <!-- Resultados -->
        <?php if ($status === 'encontrado'): ?>
            <?php foreach ($carros as $id => $carro): ?>
                <h3>🚗 Carro: <?= htmlspecialchars($carro['marca']) ?> <?= htmlspecialchars($carro['modelo']) ?> (<?= htmlspecialchars($carro['placa']) ?>)</h3>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Placa</th>
                        <th>Renavam</th>
                        <th>Cor</th>
                        <th>Ano</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                    <tr>
                        <td><?= $carro['id_carro'] ?></td>
                        <td><?= $carro['marca'] ?></td>
                        <td><?= $carro['modelo'] ?></td>
                        <td><?= $carro['placa'] ?></td>
                        <td><?= $carro['renavan'] ?></td>
                        <td><?= $carro['cor'] ?></td>
                        <td><?= $carro['ano'] ?></td>
                        <td><?= $carro['status'] ?></td>
                        <td>
                            <form method="post" action="excluir_carro.php" style="display:inline;">
                                <input type="hidden" name="id_carro" value="<?= $carro['id_carro'] ?>">
                                <button type="submit">Excluir</button>
                            </form>
                            <form method="get" action="editar_carro.php" style="display:inline; margin-left:5px;">
                                <input type="hidden" name="id_carro" value="<?= $carro['id_carro'] ?>">
                                <button type="submit">Editar</button>
                            </form>
                        </td>
                    </tr>
                </table>

                <!-- Histórico de aluguéis -->
                <h4>📄 Histórico de Aluguéis</h4>
                <?php if ($historicos[$id]->num_rows > 0): ?>
                    <table>
                        <tr>
                            <th>Cliente</th>
                            <th>CPF</th>
                            <th>Data de Aluguel</th>
                            <th>Data de Devolução</th>
                            <th>Status</th>
                        </tr>
                        <?php while ($aluguel = $historicos[$id]->fetch_assoc()): ?>
                            <?php
                                $hoje = date('Y-m-d');
                                $statusAluguel = ($aluguel['dt_devolucao'] >= $hoje) ? 'Ativo' : 'Finalizado';
                                $classeStatus = ($statusAluguel === 'Ativo') ? 'status-ativo' : 'status-finalizado';
                            ?>
                            <tr>
                                <td><?= $aluguel['nome'] ?></td>
                                <td><?= $aluguel['cpf'] ?></td>
                                <td><?= $aluguel['dt_aluguel'] ?></td>
                                <td><?= $aluguel['dt_devolucao'] ?></td>
                                <td class="<?= $classeStatus ?>"><?= $statusAluguel ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>Este carro ainda não foi alugado.</p>
                <?php endif; ?>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>
