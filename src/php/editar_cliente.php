<?php
include('../database/conexao.php'); 
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $id_cliente     = intval($_POST['id_cliente']);
    $nome           = $_POST['nome'];
    $cpf            = $_POST['cpf'];
    $email          = $_POST['email'];
    $telefone       = $_POST['telefone'];
    $dt_nascimento  = $_POST['dt_nascimento'];
    $cep            = $_POST['cep'];
    $rua            = $_POST['rua'];
    $numero_casa    = $_POST['numero_casa'];
    $bairro         = $_POST['bairro'];
    $cidade         = $_POST['cidade'];
    $profissao      = $_POST['profissao'];

    try {
        // Prepara a query de atualização
        $sql = "UPDATE clientes SET 
                    nome = ?, cpf = ?, email = ?, telefone = ?, dt_nascimento = ?, 
                    cep = ?, rua = ?, numero_casa = ?, bairro = ?, cidade = ?, profissao = ?
                WHERE id_cliente = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param(
            "sssssssisisi",
            $nome, $cpf, $email, $telefone, $dt_nascimento,
            $cep, $rua, $numero_casa, $bairro, $cidade, $profissao, $id_cliente
        );
        $stmt->execute();

        // Exibe mensagem de sucesso
        echo "<h2>✅ Cliente atualizado com sucesso!</h2>";
        echo "<div style='margin-top: 20px;'>
                <a href='clientes.php'><button>Voltar para lista de clientes</button></a>
              </div>";
    } catch (Exception $e) {
        // Exibe mensagem de erro
        echo "<h2 style='color:red;'>❌ Erro ao atualizar cliente.</h2>";
        echo "<p>Detalhes técnicos: " . $e->getMessage() . "</p>";
        echo "<div style='margin-top: 20px;'>
                <a href='clientes.php'><button>Voltar</button></a>
              </div>";
    }
} else {
    // Acesso direto sem envio de formulário
    echo "<script>
        alert('Acesso inválido.');
        window.location.href = 'clientes.php';
    </script>";
    exit;
}
?>
