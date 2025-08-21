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

// Se o formulário for enviado, busca o usuário pelo id ou nome.
if ($_SERVER["REQUEST_METHOD"] ==  "POST"){

   if (!empty($_POST['busca_usuario'])){
    $busca = trim($_POST['busca_usuario']);

    // Verifica se a busca é um número (id) ou um nome
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM usuario WHERE nome like :busca_nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se o usuário não for encontrado, exibe um alerta 
    if(!$usuario) {
        echo "<script>alert('Usuário não encontrado.');</script>";
    }
}
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Usuário</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Certifique-se que o Javascript esta sendo carregado corretamente -->
    <script src="scripts.js"></script>
</head>
<body>
    <h2> Lista de Usuarios </h2>
        <!-- Formulário para buscar usuário -->
        <form action="alterar_usuario.php" method="POST">
            <label for="busca_usuario"> Digite o ID ou Nome do usuário:</label>
            <input type="text" id="busca_usuario" name="busca_usuario" required onkeyup="buscarSugestoes()">

            <div id = "sugestoes"></div>
            <button type="submit">Buscar</button> 
         </form>

    <?php if($usuario): ?>
        <form action="processa_alteracao_usuario.php" method="POST">
            <input type="hidden" name="id_usuario" value="<?=htmlspecialchars($usuario['id_usuario'])?>">

            <label for="nome"> Nome:</label>
            <input type="text" id="nome" name="nome" value="<?=htmlspecialchars($usuario['nome'])?>" required>

            <label for="email"> Email:</label>
            <input type="text" id="email" name="email" value="<?=htmlspecialchars($usuario['email'])?>" required>

            <label for="id_perfil"> Perfil:</label>
            <select name="id_perfil" id="id_perfil">
                <option value="1" <?=($usuario['id_perfil'] == 1 ? 'selected': '' )?>> Administrador </option>
                <option value="2" <?=($usuario['id_perfil'] == 2 ? 'selected': '' )?>> Secretária </option>
                <option value="3" <?=($usuario['id_perfil'] == 3 ? 'selected': '' )?>> Almoxarife </option>
                <option value="4" <?=($usuario['id_perfil'] == 4 ? 'selected': '' )?>> Cliente </option>
            </select>

            <!-- Se o usuário logado for adm, exibir opção de alterar senha -->
            <?php if ($_SESSION['perfil'] === 1):  ?>
                <label for"nova_senha"> Nova Senha:</label>
                <input type="password" id="nova_senha" name="nova_senha">
                <?php endif; ?>

                <button type="submit"> Alterar</button>
                <button type="reset"> Cancelar</button>
        </form>     
        <?php endif; ?>
         <a href="principal.php"> Voltar </a>       
</body>
</html>