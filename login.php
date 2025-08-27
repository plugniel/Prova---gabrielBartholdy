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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required>
        
        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>
        
        <button type="submit">Entrar</button>
        </form>

        <p><a class = "btn btn-outline-primary" href="recuperar_senha.php">Esqueci minha senha </a></p>
        
</body>
</html>