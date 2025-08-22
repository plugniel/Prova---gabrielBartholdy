<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário tem permissão de adm 
if ($_SESSION['perfil']!= 1) {
    echo"<script>alert('Acesso negado.');window.location.href='principal.php';</script>";
    exit();
}
$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];

// DEFINIÇÃO DAS PERMISSÕES POR PERFIL

$permissoes = [
    
    1=>
[
    "Cadastrar"=>["cadastro_usuario.php","cadastro_perfil.php","cadastro_cliente.php","cadastro_fornecedor.php","cadastro_produto.php","cadastro_funcionario.php"],
    "Buscar"=>["buscar_usuario.php","buscar_perfil.php","buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php","buscar_funcionario.php"],
    "Alterar"=>["alterar_usuario.php","alterar_perfil.php","alterar_cliente.php","alterar_fornecedor.php","alterar_produto.php","alterar_funcionario.php"],
    "Excluir"=>["excluir_usuario.php","excluir_perfil.php","excluir_cliente.php","excluir_fornecedor.php","excluir_produto.php","excluir_funcionario.php"]],

    2=>
[
    "Cadastrar"=>["cadastro_cliente.php"],
    "Buscar"=>["buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php"],
    "Alterar"=>["alterar_cliente.php","alterar_fornecedor.php"]],

    3=>
[
    "Cadastrar"=>["cadastro_fornecedor.php","cadastro_produto.php"],
    "Buscar"=>["buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php"],
    "Alterar"=>["alterar_fornecedor.php","alterar_produto.php"],
    "Excluir"=>["excluir_produto.php"]],

    4=>
[
    "Cadastrar"=>["cadastro_cliente.php"],
    "Buscar"=>["buscar_produto.php"],
    "Alterar"=>["alterar_cliente.php"]],

];

// OBTENDO AS OPÇÕS DISPONIVEIS PARA O PERFIL LOGADO

$opcoes_menu = $permissoes[$id_perfil];

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
        $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <nav>
            <ul class="menu">
                <?php foreach($opcoes_menu as $categoria=>$arquivos): ?>
                <li class="dropdown">
                    <a href="#"><?= $categoria ?></a>
                    <ul class="dropdown-menu">
                        <?php foreach($arquivos as $arquivo): ?>
                        <li>
                            <a href="<?= $arquivo ?>"><?= ucfirst(str_replace("_"," ",basename($arquivo,".php")))?></a>
                        </li>
                            <?php endforeach; ?>
                    </ul>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <br>
    <center><h2> Lista de Usuarios </h2></center>
        <!-- Formulário para buscar usuário -->
        <form action="alterar_usuario.php" method="POST">
            <label for="busca_usuario"> Digite o ID ou Nome do usuário:</label>
            <input type="text" id="busca_usuario" name="busca_usuario" required onkeyup="buscarSugestoes()">

            <button type="submit"class ="btn btn-primary">Buscar</button> 
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

                <button type="submit"class ="btn btn-primary"> Alterar</button>
                <br>
                <button type="reset"class ="btn btn-primary"> Cancelar</button>
        </form>     
        <?php endif; ?>
         <center><a href="principal.php"class ="btn btn-primary"> Voltar </a></center>
</body>
</html>