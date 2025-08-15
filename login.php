<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário já está logado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o usuário existe
    $sql ="SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Login bem-sucedido, define variáveis de sessão
        $_SESSION['usuario'] = $usuario['nome'];
        $_SESSION['perfil'] = $usuario['id_perfil'];
        $_SESSION['id_usuario'] = $usuario['id_usuario'];

        // Veriifica se a senha é temporária
        if ($usuario['senha_temporaria']) {
            // Redireciona para a página de alteração de senha
            header('Location: alterar_senha.php');
            exit();
        
    } else {
        // Redireciona para a pagina principal
        header('Location: principal.php');
        exit();
    }
}else {
    // Login invalido
    echo "<script>alert('E-mail ou senha inválidos.');window.location.href='login.php';</script>";
}
}   
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required>
        
        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>
        
        <button type="submit">Entrar</button>
        </form>

        <p><a href="recuperar_senha.php">Esqueci minha senha </a></p>
        
</body>
</html>