<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário tem permissão de adm 
if ($_SESSION['perfil']!= 1) {
    echo"<script>alert('Acesso negado.');window.location.href='principal.php';</script>";
    exit();
}

// Inicializa as variaveis 
$usuario = null;

// Busca todos os usuários cadastrados em ordem alfabética
$sql = "SELECT * FROM usuario ORDER BY nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchALL(PDO::FETCH_ASSOC);

// Se um id for passado via GET, excluir o usuário 
if (isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_usuario = $_GET['id'];
    
    // excluir o usuario do banco de dados 
    $sql = "DELETE FROM usuario WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

    if ($stmt->execute()){
        echo "<script>alert('Usuário excluído com sucesso!');window.location.href='excluir_usuario.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir usuário.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir usuário</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2> Excluir Usuário</h2>
    <?php if(!empty($usuarios)):?>
        <table border = "1">
            <tr> 
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Ações</th>
            </tr>
        
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td> <?= htmlspecialchars($usuario['id_usuario']) ?> </td>
                    <td> <?= htmlspecialchars($usuario['nome']) ?> </td>
                    <td> <?= htmlspecialchars($usuario['email']) ?> </td>
                    <td> <?= htmlspecialchars($usuario['id_perfil']) ?> </td>
                    <td>
                        <a href="excluir_usuario.php?id=<?= htmlspecialchars($usuario['id_usuario']) ?>"onclick= "return confirm('Tem certeza que deseja excluir este usuário?)">
                            Excluir
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>     
        </table>
            <?php else: ?>
            <p> Nenhum usuário encontrado.</p>
    <?php endif; ?>
    <a href="principal.php">Voltar</a>      
</body>
</html>