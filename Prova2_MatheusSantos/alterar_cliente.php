<?php
session_start();
require 'conexao.php';

// Verifica se o usuário tem permissão de ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

$cliente = null;

// Se veio o ID via GET, busca o cliente
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_cliente = $_GET['id'];
    $sql = "SELECT * FROM cliente WHERE id_cliente = :id_cliente";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Se o formulário de busca for enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['busca_cliente'])) {
    $busca = trim($_POST['busca_cliente']);
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM cliente WHERE id_cliente = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM cliente WHERE nome_cliente LIKE :busca_nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$cliente) {
        echo "<script>alert('Cliente não encontrado!');</script>";
    }
}

// Se o formulário de alteração for enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_cliente'], $_POST['nome'], $_POST['email'], $_POST['id_perfil'])) {
    $id_cliente = $_POST['id_cliente'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $id_perfil = $_POST['id_perfil'];

    // Validações do lado do servidor
    if (preg_match('/[0-9]/', $nome)) {
        echo "<script>alert('O nome não pode conter números!');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('E-mail inválido!');</script>";
    } elseif (strlen(preg_replace('/\D/', '', $telefone)) !== 11) {
        echo "<script>alert('Telefone deve conter 11 dígitos!');</script>";
    } else {
        $sql = "UPDATE cliente SET nome_cliente = :nome, email = :email, telefone = :telefone, endereco = :endereco, id_funcionario_responsavel = :id_perfil WHERE id_cliente = :id_cliente";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':id_perfil', $id_perfil);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Cliente alterado com sucesso!'); window.location.href='buscar_cliente.php';</script>";
            exit();
        } else {
            echo "<script>alert('Erro ao alterar cliente!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Cliente</title>
    <link rel="stylesheet" href="crud.css">
</head>
<body>
    <h2 style="font-size: 40px;">Matheus Etelvino dos Santos</h2>
    <h2>Alterar Cliente</h2>
    
    <!-- Formulário para buscar cliente pelo ID ou Nome -->
    <form action="alterar_cliente.php" method="POST">
        <label for="busca_cliente">Digite o ID ou Nome do cliente:</label>
        <input type="text" id="busca_cliente" name="busca_cliente" required>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($cliente): ?>
        <!-- Formulário para alterar cliente -->
        <form action="alterar_cliente.php" method="POST" onsubmit="return validarCliente()">
            <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($cliente['id_cliente']) ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($cliente['nome_cliente']) ?>" required oninput="validarNome(this)">

            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone']) ?>" required placeholder="(00) 00000-0000">

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($cliente['endereco']) ?>" required>

            <label for="id_perfil">Perfil:</label>
            <select id="id_perfil" name="id_perfil" required>
                <option value="1" <?= $cliente['id_funcionario_responsavel'] == 1 ? 'selected' : '' ?>>Administrador</option>
                <option value="2" <?= $cliente['id_funcionario_responsavel'] == 2 ? 'selected' : '' ?>>Secretaria</option>
                <option value="3" <?= $cliente['id_funcionario_responsavel'] == 3 ? 'selected' : '' ?>>Almoxarife</option>
                <option value="4" <?= $cliente['id_funcionario_responsavel'] == 4 ? 'selected' : '' ?>>Cliente</option>
            </select>

            <button type="submit">Alterar</button>
            <button type="button" onclick="window.location.href='buscar_cliente.php'">Cancelar</button>
        </form>
    <?php endif; ?>

    <a href="principal.php" class="botao-voltar">Voltar</a>

    <script>
    // Validação do nome (não permite números)
    function validarNome(input) {
        input.value = input.value.replace(/[0-9]/g, '');
    }

    // Máscara para telefone
    document.getElementById('telefone')?.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        
        let formattedValue = '';
        if (value.length > 0) {
            formattedValue = '(' + value.substring(0, 2);
            if (value.length > 2) {
                formattedValue += ') ' + value.substring(2, 7);
            }
            if (value.length > 7) {
                formattedValue += '-' + value.substring(7, 11);
            }
        }
        
        e.target.value = formattedValue;
    });

    // Validação completa do formulário
    function validarCliente() {
        const nome = document.getElementById('nome').value;
        const telefone = document.getElementById('telefone').value.replace(/\D/g, '');
        const email = document.getElementById('email').value;
        
        // Validação do nome
        if (/[0-9]/.test(nome)) {
            alert('O nome não pode conter números!');
            return false;
        }
        
        // Validação do telefone
        if (telefone.length !== 11) {
            alert('Telefone inválido! Deve conter 11 dígitos (DDD + número)');
            return false;
        }
        
        // Validação do email
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert('E-mail inválido!');
            return false;
        }
        
        return true;
    }
    </script>
</body>
</html>