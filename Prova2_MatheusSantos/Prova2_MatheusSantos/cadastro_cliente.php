<?php
session_start();
require_once 'conexao.php';

if($_SESSION['perfil'] !=1) {
    echo "acesso negado";
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $nome = $_POST['nome'];
    $telefone= $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST ['endereco'];
    $id_funcionario_responsavel = $_POST['id_perfil'];

    $sql = "INSERT INTO cliente (nome_cliente, telefone, email, endereco, id_funcionario_responsavel) 
        VALUES (:nome_cliente, :telefone, :email, :endereco, :id_funcionario_responsavel)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_cliente', $nome);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':id_funcionario_responsavel', $id_funcionario_responsavel);

    if($stmt->execute()){
        echo "<script>alert('Cliente cadastrado com sucesso!');</script>";
    }else{
        echo "<script>alert('Erro ao cadastrar cliente!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="crud.css">
</head>
<body>
    <h2 style="font-size: 40px;">Matheus Etelvino dos Santos</h2>
    <h2>Cadastrar Cliente</h2>

    <form action="cadastro_cliente.php" method="POST" onsubmit="return validarCliente()">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required oninput="validarNome(this)">

        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" required placeholder="(00) 00000-0000" maxlength="15">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required placeholder="exemplo@gmail.com">

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <label for="id_perfil">Perfil:</label>
        <select id="id_perfil" name="id_perfil" required>
            <option value="">Selecione...</option>
            <option value="1">Administrador</option>
            <option value="2">Secretaria</option>
            <option value="3">Amoxarife</option>
            <option value="4">Cliente</option>
        </select>

        <button type="submit">Cadastrar</button>
        <button type="button" onclick="window.location.href='principal.php'">Cancelar</button>
    </form>

    <a href="principal.php" class="botao-voltar">Voltar</a>
    
    <script>
    // Função para validar nome (não permite números)
    function validarNome(input) {
        input.value = input.value.replace(/[0-9]/g, '');
    }

    // Máscara para telefone
    document.getElementById('telefone').addEventListener('input', function(e) {
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

    // Validação do formulário
    function validarCliente() {
        const nome = document.getElementById('nome').value;
        const telefone = document.getElementById('telefone').value.replace(/\D/g, '');
        const email = document.getElementById('email').value;
        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        
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
        
        // Validação do e-mail
        if (!emailRegex.test(email)) {
            alert('E-mail inválido! Deve conter um domínio válido (ex: usuario@gmail.com)');
            return false;
        }
        
        return true;
    }
    </script>
</body>
</html>