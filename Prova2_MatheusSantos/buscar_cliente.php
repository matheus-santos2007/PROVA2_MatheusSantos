<?php

session_start();
require_once 'conexao.php';

//Verifica se o usuario tem permissão de ADM ou SECRETARIA

if($_SESSION['perfil'] !=1 && $_SESSION['perfil'] !=2) {
    echo "<script>alert('acesso negado!');window.location.href='principal.php';</script>";
    exit();

}

$clientes = []; //Inicializa a variavel para evitar erros

// Se o formulário for enviado, busca o cliente pelo id ou nome

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])) { // Corrigido para verificar se 'busca' não está vazio
    $busca = trim($_POST['busca']);
} else {
    $busca = ''; // Inicializa a variável $busca como uma string vazia caso não seja definida
}

// VERIFICA SE A BUSCA É UM NÚMERO (id) ou um nome
if (is_numeric($busca)) {
    $sql = "SELECT * FROM cliente WHERE id_cliente = :busca ORDER BY nome_cliente ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
} elseif (!empty($busca)) {
    $sql = "SELECT * FROM cliente WHERE nome_cliente LIKE :busca_nome ORDER BY nome_cliente ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
} else {
    $sql = "SELECT * FROM cliente ORDER BY nome_cliente ASC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscas Cliente</title>
    <link rel="stylesheet" href="crud.css">
</head>
<body>
    <h2 style="font-size: 40px;">Matheus Etelvino dos Santos</h2>
    <h2>Lista de Clientes</h2>

    <!--Formulario para buscar clientes -->

    <form action="buscar_cliente.php" method="POST">
        <label for="busca">Digite o ID ou Nome (opcional)</label>
        <input type="text" id="busca" name="busca">
        <button type="submit">Pesquisar</button>
    </form>

    <?php if(!empty ($clientes)): ?>
        <table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Perfil</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientes as $cliente): ?>
        <tr>
            <td><?=htmlspecialchars($cliente['id_cliente']) ?></td>
            <td><?=htmlspecialchars($cliente['nome_cliente']) ?></td>
            <td><?=htmlspecialchars($cliente['email']) ?></td>
            <td><?=htmlspecialchars($cliente['id_funcionario_responsavel']) ?></td>
            <td>
                <a href="alterar_cliente.php?id=<?=htmlspecialchars($cliente['id_cliente']) ?>">Alterar</a>
                <a href="excluir_cliente.php?id=<?=htmlspecialchars($cliente['id_cliente']) ?>" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p>Nenhum cliente encontrado.</p>
<?php endif; ?>
<a href="principal.php" class="botao-voltar">Voltar</a>

    
</body>
</html>