<?php
// Inclui o arquivo de conexão com o banco de dados
include('../database/conexao.php');

// Ativa o modo de relatório de erros do MySQLi para facilitar o debug
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Variável para armazenar o status da operação (sucesso, duplicado, erro)
$status = null;

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados enviados pelo formulário
    $marca = $_POST['marca'];
    $placa = $_POST['placa'];
    $renavan = $_POST['renavan'];
    $cor = $_POST['cor'];
    $ano = $_POST['ano'];
    $modelo = $_POST['modelo'];

    try {
        // Prepara a query de inserção no banco
        $sql = "INSERT INTO carros (marca, placa, renavan, cor, ano, modelo, status) VALUES (?, ?, ?, ?, ?, ?, 'disponível')";
        $stmt = $conexao->prepare($sql);

        // Associa os parâmetros à query (s = string, i = inteiro)
        $stmt->bind_param('ssisss', $marca, $placa, $renavan, $cor, $ano, $modelo);

        // Executa a query
        $stmt->execute();

        // Define o status como sucesso
        $status = 'sucesso';
    } catch (mysqli_sql_exception $e) {
        // Verifica se o erro foi por duplicidade (placa já cadastrada)
        if (str_contains($e->getMessage(), 'Duplicate entry')) {
            $status = 'duplicado';
        } else {
            $status = 'erro';
        }
    }

    // Fecha o statement se foi criado
    if (isset($stmt)) {
        $stmt->close();
    }

    // Fecha a conexão com o banco
    $conexao->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Carros</title>
</head>
<body>

    <!-- Cabeçalho com logo e botão -->
    <header>
        <img src="logo.png" alt="logo">
        <button>Entrar</button>
    </header>

    <main>
        <div>
            <h2>🚗 Carros</h2>
            <!-- Mensagens de status após tentativa de cadastro -->
            <?php if ($status === 'sucesso'): ?>
                <div>✅ Carro cadastrado com sucesso!</div>
            <?php elseif ($status === 'duplicado'): ?>
                <div>⚠️ Esta placa já está cadastrada.</div>
            <?php elseif ($status === 'erro'): ?>
                <div>❌ Erro ao cadastrar carro. Tente novamente.</div>
            <?php endif; ?>
            <!-- Formulário de cadastro de carro -->
            <form action="carros.php" method="POST">
                <div>
                    <label for="marca">Marca</label>
                    <input type="text" name="marca" required>
                    <br><br>
                    <label for="placa">Placa</label>
                    <input type="text" name="placa" required>
                    <br><br>
                    <label for="renavan">Renavam</label>
                    <input type="number" name="renavan" required>
                    <br><br>
                    <label for="cor">Cor</label>
                    <input type="text" name="cor" required>
                    <br><br>
                    <label for="ano">Ano Fabricação</label>
                    <input type="text" name="ano" required>
                    <br><br>
                    <label for="modelo">Modelo</label>
                    <input type="text" name="modelo" required>
                </div>
                <div>
                    <button type="submit">Cadastrar</button>
                    <button type="reset">Limpar</button>
        
                </div>
            </form>
            <a href="../../index.html">
                <button>Voltar para página inicial</button>
            </a>
        </div>
    </main>

    <!-- Rodapé -->
    <footer>
        <p>Todos os direitos reservados &copy; 2025</p>
    </footer>
</body>
</html>

