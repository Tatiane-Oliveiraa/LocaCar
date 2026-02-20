<?php
include('../database/conexao.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Inicializa variáveis
$status = null;
$resultado = null;
$historicos = [];

// Processa a busca se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = $_POST['cpf'];

    if (empty($cpf)) {
        $status = 'vazio';
    } else {
        try {
            // Consulta clientes com CPF semelhante
            $sql = "SELECT * FROM clientes WHERE cpf LIKE ?";
            $stmt = $conexao->prepare($sql);
            $likeCpf = "%$cpf%";
            $stmt->bind_param("s", $likeCpf);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows === 0) {
                $status = 'nao_encontrado';
            } else {
                $status = 'encontrado';

                // Para cada cliente encontrado, busca histórico de aluguéis
                while ($cliente = $resultado->fetch_assoc()) {
                    $id_cliente = $cliente['id_cliente'];

                    $stmtHistorico = $conexao->prepare("
                        SELECT a.dt_aluguel, a.dt_devolucao, c.marca, c.modelo, c.placa
                        FROM alugar a
                        JOIN carros c ON a.id_carro = c.id_carro
                        WHERE a.id_cliente = ?
                    ");
                    $stmtHistorico->bind_param("i", $id_cliente);
                    $stmtHistorico->execute();
                    $historicos[$id_cliente] = [
                        'dados' => $cliente,
                        'alugueis' => $stmtHistorico->get_result()
                    ];
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
    <title>Consultar Clientes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <main>
        <h2>🔍 Consultar Clientes</h2>
        <!-- Formulário de busca -->
        <form method="POST">
            <label for="cpf">Digite o CPF:</label><br>
            <input type="text" name="cpf" id="cpf" required>
            <br><br>
            <button type="submit">Consultar</button>
        </form>
        <!-- Mensagens de status -->
        <?php if ($status === 'vazio'): ?>
            <div class="mensagem">⚠️ Campo de busca vazio. Por favor, insira um CPF.</div>
        <?php elseif ($status === 'nao_encontrado'): ?>
            <div class="mensagem">❌ Nenhum cliente encontrado com esse CPF.</div>
        <?php elseif ($status === 'erro'): ?>
            <div class="mensagem">❌ Erro ao consultar. Tente novamente.</div>
        <?php endif; ?>
        <!-- Tabela de resultados -->
        <?php if ($status === 'encontrado'): ?>
            <?php foreach ($historicos as $id => $info): ?>
                <?php $cliente = $info['dados']; ?>
                <h3>👤 Cliente: <?= htmlspecialchars($cliente['nome']) ?> (CPF: <?= htmlspecialchars($cliente['cpf']) ?>)</h3>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Data de Nascimento</th>
                        <th>CEP</th>
                        <th>Rua</th>
                        <th>Número</th>
                        <th>Bairro</th>
                        <th>Cidade</th>
                        <th>Profissão</th>
                        <th>Ações</th>
                    </tr>
                    <tr>
                        <td><?= $cliente['id_cliente'] ?></td>
                        <td><?= $cliente['email'] ?></td>
                        <td><?= $cliente['telefone'] ?></td>
                        <td><?= $cliente['dt_nascimento'] ?></td>
                        <td><?= $cliente['cep'] ?></td>
                        <td><?= $cliente['rua'] ?></td>
                        <td><?= $cliente['numero_casa'] ?></td>
                        <td><?= $cliente['bairro'] ?></td>
                        <td><?= $cliente['cidade'] ?></td>
                        <td><?= $cliente['profissao'] ?></td>
                        <td>
                            <form method="post" action="excluir.php" style="display:inline;">
                                <input type="hidden" name="id_cliente" value="<?= $cliente['id_cliente'] ?>">
                                <button type="submit">Excluir</button>
                            </form>
                            <form method="get" action="editar.php" style="display:inline; margin-left:5px;">
                                <input type="hidden" name="id_cliente" value="<?= $cliente['id_cliente'] ?>">
                                <button type="submit">Editar</button>
                            </form>
                        </td>
                    </tr>
                </table>
                <!-- Histórico de aluguéis -->
                <h4>📄 Histórico de Aluguéis</h4>
                <?php if ($info['alugueis']->num_rows > 0): ?>
                    <table>
                        <tr>
                            <th>Data de Aluguel</th>
                            <th>Data de Devolução</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Placa</th>
                            <th>Status</th>
                        </tr>
                        <?php while ($aluguel = $info['alugueis']->fetch_assoc()): ?>
                            <?php
                                $hoje = date('Y-m-d');
                                $statusAluguel = ($aluguel['dt_devolucao'] >= $hoje) ? 'Ativo' : 'Finalizado';
                                $classeStatus = ($statusAluguel === 'Ativo') ? 'status-ativo' : 'status-finalizado';
                            ?>
                            <tr>
                                <td><?= $aluguel['dt_aluguel'] ?></td>
                                <td><?= $aluguel['dt_devolucao'] ?></td>
                                <td><?= $aluguel['marca'] ?></td>
                                <td><?= $aluguel['modelo'] ?></td>
                                <td><?= $aluguel['placa'] ?></td>
                                <td class="<?= $classeStatus ?>"><?= $statusAluguel ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>Este cliente ainda não possui aluguéis registrados.</p>
                <?php endif; ?>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>
