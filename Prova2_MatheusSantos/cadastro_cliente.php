<?php

session_start();
require_once 'conexao.php';

//Verifica se o usuario tem permissão
//supondo que o perfil 1 seja o administrador
if($_SESSION['perfil'] !=1) {
    echo "acesso negado";

}

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $id_funcionario_responsavel = $_POST['id_perfil'];

    $sql = "INSERT INTO cliente (nome_cliente, email, id_funcionario_responsavel) VALUES (:nome_cliente, :email, :id_funcionario_responsavel)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_cliente', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id_funcionario_responsavel', $id_funcionario_responsavel);

    if($stmt->execute()){
        echo "<script>alert('cliente cadastrado com sucesso!');</script>";
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
        <input type="text" id="nome" name="nome" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <label for="id_perfil">Perfil:</label>
        <select id="id_perfil" name="id_perfil">
            <option value="1">Administrador </option>
            <option value="2">Secretaria</option>
            <option value="3">Amoxarife</option>
            <option value="4">Cliente</option>
    
        </select>

        <button type="submit">Cadastrar</button>
        <button type="reset" onclick="window.location.href='principal.php'">Cancelar</button>
    </form>

    <a href="principal.php" class="botao-voltar">Voltar</a>
    <script src="validacao_cliente.js"></script>

</body>
</html>