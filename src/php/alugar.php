<?php
include('../../src/database/conexao.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Variável para armazenar o status da operação
$status = null; // 'cliente_nao_encontrado', 'carros_nao_encontrados', 'aluguel_sucesso', 'erro_aluguel', etc.

$cliente = null;
$carros = null;
$marca_desejada = '';

// 🧩 Etapa 1: Buscar cliente pelo CPF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cpf'])) {
    $cpf = $_POST['cpf'];

    try {
        $stmt = $conexao->prepare("SELECT * FROM clientes WHERE cpf = ?");
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $status = 'cliente_nao_encontrado';
        } else {
            $cliente = $result->fetch_assoc();
        }
    } catch (Exception $e) {
        $status = 'erro_busca_cliente';
    }
}

// 🧾 Etapa 2: Buscar carros pela marca desejada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['marca_desejada']) && isset($_POST['id_cliente']) && !isset($_POST['registrar'])) {
    $marca_desejada = $_POST['marca_desejada'];
    $id_cliente = $_POST['id_cliente'];

    try {
        $stmt = $conexao->prepare("SELECT * FROM carros WHERE status = 'disponível' AND marca LIKE ?");
        $like_marca = '%' . $marca_desejada . '%';
        $stmt->bind_param("s", $like_marca);
        $stmt->execute();
        $carros = $stmt->get_result();

        if ($carros->num_rows === 0) {
            $status = 'carros_nao_encontrados';
        }

        // Recarrega dados do cliente
        $stmt_cliente = $conexao->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
        $stmt_cliente->bind_param("i", $id_cliente);
        $stmt_cliente->execute();
        $cliente = $stmt_cliente->get_result()->fetch_assoc();
    } catch (Exception $e) {
        $status = 'erro_busca_carros';
    }
}

// ✅ Etapa 3: Registrar aluguel
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
    $id_cliente = $_POST['id_cliente'];
    $id_carro = $_POST['id_carro'];
    $dt_aluguel = $_POST['dt_aluguel'];
    $dt_devolucao = $_POST['dt_devolucao'];

    try {
        $stmt = $conexao->prepare("INSERT INTO alugar (id_cliente, id_carro, dt_aluguel, dt_devolucao) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $id_cliente, $id_carro, $dt_aluguel, $dt_devolucao);
        $stmt->execute();

        $stmt2 = $conexao->prepare("UPDATE carros SET status = 'alugado' WHERE id_carro = ?");
        $stmt2->bind_param("i", $id_carro);
        $stmt2->execute();

        $status = 'aluguel_sucesso';
    } catch (Exception $e) {
        $status = 'erro_aluguel';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Alugar Carro</title>
    
</head>
<body>

<!-- Cabeçalho com logo e botão -->
    <header>
        <img src="logo.png" alt="logo">
        <button>Entrar</button>
    </header>

    <main>
        <h2>🚗 Aluguel de Carro</h2>
        <!-- 🔔 Mensagens de status -->
        <?php if ($status === 'cliente_nao_encontrado'): ?>
            <div style="color: red;">❌ Cliente não cadastrado!</div>
            <a href="clientes.php">
                <button style="padding: 10px 20px;">Voltar para cadastro</button>
            </a>
        <?php elseif ($status === 'carros_nao_encontrados'): ?>
            <div style="color: orange;">⚠️ Nenhum carro disponível para a marca informada.</div>
        <?php elseif ($status === 'aluguel_sucesso'): ?>
            <div style="color: green;">✅ Aluguel registrado com sucesso!</div>
        <?php elseif ($status === 'erro_aluguel'): ?>
            <div style="color: red;">❌ Erro ao registrar aluguel. Tente novamente.</div>
        <?php elseif ($status === 'erro_busca_cliente'): ?>
            <div style="color: red;">❌ Erro ao buscar cliente.</div>
        <?php elseif ($status === 'erro_busca_carros'): ?>
            <div style="color: red;">❌ Erro ao buscar carros.</div>
        <?php endif; ?>
        <!-- 🧾 Etapa 1: Buscar cliente -->
        <?php if (!$cliente && $status !== 'cliente_nao_encontrado'): ?>
            <form method="POST">
                <label>Digite o CPF do cliente:</label><br>
                <input type="text" name="cpf" required>
                <button type="submit">Buscar</button>
            </form>
        <!-- 🧾 Etapa 2: Informar marca desejada -->
        <?php elseif ($cliente && !$carros && $status !== 'carros_nao_encontrados'): ?>
            <h3>Cliente encontrado:</h3>
            <p>Nome: <?= htmlspecialchars($cliente['nome']) ?></p>
            <p>CPF: <?= htmlspecialchars($cliente['cpf']) ?></p>
            <form method="POST">
                <input type="hidden" name="id_cliente" value="<?= $cliente['id_cliente'] ?>">
                <label>Marca desejada:</label><br>
                <input type="text" name="marca_desejada" required>
                <button type="submit">Buscar carros</button>
            </form>
        <!-- 🧾 Etapa 3: Selecionar carro e registrar aluguel -->
        <?php elseif ($carros): ?>
            <h3>Cliente encontrado:</h3>
            <p>Nome: <?= htmlspecialchars($cliente['nome']) ?></p>
            <p>CPF: <?= htmlspecialchars($cliente['cpf']) ?></p>
            <form method="POST">
                <input type="hidden" name="id_cliente" value="<?= $cliente['id_cliente'] ?>">
                <input type="hidden" name="registrar" value="1">
                <label>Carros disponíveis da marca "<?= htmlspecialchars($marca_desejada) ?>":</label><br>
                <select name="id_carro" required>
                    <?php while ($carro = $carros->fetch_assoc()): ?>
                        <option value="<?= $carro['id_carro'] ?>">
                            <?= $carro['marca'] ?> - <?= $carro['modelo'] ?> (<?= $carro['ano'] ?>)
                        </option>
                    <?php endwhile; ?>
                </select><br><br>
                <label>Data de aluguel:</label><br>
                <input type="date" name="dt_aluguel" required><br><br>
                <label>Data de devolução:</label><br>
                <input type="date" name="dt_devolucao" required><br><br>
                <button type="submit">Confirmar aluguel</button>
                <button type="reset">Limpar</button>
        
            </form>
            <a href="../../index.html">
                <button>Voltar para página inicial</button>
            </a>
        
        <?php endif; ?>
    </main>

    <!-- Rodapé -->
    <footer>
        <p>Todos os direitos reservados &copy; 2025</p>
    </footer>
</body>
</html>
