<?php
session_start();
require_once 'conexao.php';
require_once 'funcoes_email.php'; // Arquivo com funções que geram a senha e silulam o envio

// Verifica se o usuário existe
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Verifica se o usuário existe
    $sql ="SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Gera uma nova senha temporária
        $senha_temporaria = gerarSenhaTemporaria();
        $senha_hash = password_hash($senha_temporaria, PASSWORD_DEFAULT);
        
        // Atualiza a senha do usuário no banco de dados
        $sql = "UPDATE usuario SET senha = :senha, senha_temporaria = TRUE WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Envia a nova senha para o e-mail do usuário
        simularEnvioEmail($email, $senha_temporaria);
        echo "<script>alert('Uma nova senha temporaria foi gerada e enviada (simulação). Verifique o arquivo emails_simulados.txt');window.location.href='login.php';</script>";

    } else {
        echo "<script>alert('E-mail não encontrado.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar senha</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Recuperar Senha</h2>
    <form action="recuperar_senha.php" method="POST">
        <label for="email">Digite seu E-mail cadastrado:</label>
        <input type="email" name="email" id="email" required>
        
        <button type="submit">Enviar nova senha</button>
    </form>
    <p><a href="login.php">Voltar para o login</a></p>
    
</body>
</html>