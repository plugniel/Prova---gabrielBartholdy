<?php
session_start();
require_once 'conexao.php';

if($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso Negado');window.location.href='principal.php'</script>";
    exit();
}
//inicializa a variavel para evitar erros 
$usuario = [];
//se o formulario for enviado , busca o usuario pelo id ou nome
if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST['id_usuario'])) {
    $busca = trim($_POST['busca']);

    //verefica se a busca é um numero(ID) ou nome
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :busca ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM usuario WHERE nome LIKE :busca_nome ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR); 
    }
}else{
    $sql = "SELECT * FROM usuario ORDER BY nome ASC";
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="PT-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Lista de usuarios</h2>
    <!-- Formulario para buscar usuarios -->
    <form action="buscar_usuario.php" method="POST">
        <label for="busca">Digite o ID ou Nome(opcional):</label>
        <input type="text" id="busca" name="busca">
        <button type="submit">Pesquisar</button>
    </form>
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
                <td><?php echo htmlspecialchars($usuario['id_usuario']) ?></td>
                <td><?php echo htmlspecialchars($usuario['nome']) ?></td>
                <td><?php echo htmlspecialchars($usuario['email']) ?></td>
                <td><?php echo htmlspecialchars($usuario['id_perfil']) ?></td>
                <td>
                    <a href="alterar_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>">Alterar</a>
                    <a href="excluir_usuario.php?id=<?=htmlspecialchars($usuario['id_usuario'])?>"onclick="return confirm('tem certeza que deseja excluir esse usuario')">excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p>Nenhum usuário encontrado.</p>
    <?php endif; ?>

    <a href="principal.php">Voltar</a>
</body> 
</html>