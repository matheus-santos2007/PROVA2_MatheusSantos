<?php   
session_start();
require_once 'conexao.php';
require_once 'funcoes_email.php'; //Arquivo com as funções que geram senha e simulam o envio

if($_SERVER["REQUEST_METHOD"] =="POST"){

    $email=$_POST['email'];

    //VERIFICA SE O EMAIL EXISTE NO BANCO
    $sql="SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if($usuario){

        //Gera uma senha temporaria aleatória
        $senha_temporaria = gerarSenhaTemporaria();
        $senha_hash = password_hash($senha_temporaria, PASSWORD_DEFAULT);

        //Atualiza a senha do usuario no banco 
        $sql="UPDATE usuario SET senha = :senha, senha_temporaria = TRUE  WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        //Simula o envio do email(grava em TXT)
        simularEnvioEmail($email, $senha_temporaria);

        echo "<script>alert('Uma senha temporaria foi gerada e enviada(simulação)'.Verifique o arquivo emails_simulado.txt');window.location.href='login.php';</script>";
    }else{
        echo "<script>alert('Email não encontrado!');<script>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recupera Senha</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Recuperar Senha</h2>
    <form action="recuperar_senha.php" method="POST">
        <label for="email">Digite o seu email cadastrado:</label>
        <input type="email" id="email" name="email" required>

        <button type="submit">Enviar Senha Temporaria</button>
    </form>
    
</body>
</html>

