<?php
    include('../../src/database/conexao.php');

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $status = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $cep = $_POST['cep'];
        $rua = $_POST['rua'];
        $numero_casa = $_POST['numero_casa'];
        $bairro = $_POST['bairro'];
        $cidade = $_POST['cidade'];
        $profissao = $_POST['profissao'];
        $dt_nascimento = $_POST['dt_nascimento'];


        try {
            $sql = "INSERT INTO clientes (nome, cpf, email, telefone, cep, rua, numero_casa, bairro, cidade, profissao, dt_nascimento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('ssssisissss', $nome, $cpf, $email, $telefone, $cep, $rua, $numero_casa, $bairro, $cidade, $profissao, $dt_nascimento);
            $stmt->execute();

            $status = 'sucesso';
        } catch (mysqli_sql_exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                $status = 'duplicado';
            } else {
                $status = 'erro';
            }
        }

        if (isset($stmt)) {
            $stmt->close();
        }
        $conexao->close();
    }
?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <title>Cadastro de Clientes</title>
    </head>
    <body>

    <header>
        <img src="logo.png" alt="logo">
        <button>Entrar</button>
    </header>
        <main>
            <div>
                <h2>🧑‍✈️ Cadastro de Clientes</h2>
                <?php if ($status === 'sucesso'): ?>
                    <div>✅ Cliente cadastrado com sucesso!</div>
                <?php elseif ($status === 'duplicado'): ?>
                    <div>⚠️ Este cpf já está cadastrado.</div>
                <?php elseif ($status === 'erro'): ?>
                    <div>❌ Erro ao cadastrar cliente. Tente novamente.</div>
                <?php endif; ?>
                <form action="clientes.php" method="POST" >
                    <div>
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" required>
                        <br><br>
                        <label for="cpf">CPF</label>
                        <input type="number" name="cpf" required>
                        <br><br>
                        <label for="email">E-mail</label>
                        <input type="email" name="email" required>
                        <br><br>
                        <label for="telefone">Telefone</label>
                        <input type="text" name="telefone" required>
                        <br><br>
                        <label for="dt_nascimento">Data de Nascimento</label>
                        <input type="date" name="dt_nascimento" required>
                        <br><br>
                        <label for="cep">CEP</label>
                        <input type="number" name="cep" required>
                        <br><br>
                        <label for="rua">Rua</label>
                        <input type="text" name="rua" required>
                        <br><br>
                        <label for="numero_casa">Número da Casa</label>
                        <input type="number" name="numero_casa" required>
                        <br><br>
                        <label for="bairro">Bairro</label>
                        <input type="text" name="bairro" required>
                        <br><br>
                        <label for="cidade">Cidade</label>
                        <input type="text" name="cidade" required>
                        <br><br>
                        <label for="profissao">Profissão</label>
                        <input type="text" name="profissao" required>
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

        <footer>
            <p>Todos os direitos reservados &copy; 2025</p>
        </footer>
    </body>
    </html>