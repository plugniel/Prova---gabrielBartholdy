<?php

session_start();
require_once 'conexao.php';

if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] !=2) {
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}
// INICIALIZA A VARIAVEL PARA EVITAR ERROS

$fornecedores = [];

// SE O FORMULARIO FOR ENVIADO, BUSCA O USUARIO PELO ID OU NOME

if ($_SERVER["REQUEST_METHOD"]=="POST" && !empty($_POST['busca'])){
 $busca = trim($_POST['busca']);
 
 // VERIFICA SE A BUSCA É UM NUMERO (ID) OU UM NOME

 if(is_numeric($busca)){
    $sql =  "SELECT * FROM fornecedor WHERE id_fornecedor = :busca ORDER BY nome_fornecedor ASC";
    $stmt =$pdo->prepare($sql);
    $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
 } else {
    $sql = "SELECT * FROM fornecedor WHERE nome_fornecedor LIKE :busca_fornecedor ORDER BY nome_fornecedor ASC";
    $stmt =$pdo->prepare($sql);
    $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
 }
} else{
    $sql = "SELECT * FROM fornecedor ORDER BY nome_fornecedor ASC";
    $stmt =$pdo->prepare($sql);
}
$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];

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
    "Alterar"=>["alterar_fornecedor.php","alterar_produto.php"]],
    "Excluir"=>["excluir_produto.php"],

    4=>
[
    "Cadastrar"=>["cadastro_cliente.php"],
    "Buscar"=>["buscar_produto.php"],
    "Alterar"=>["alterar_cliente.php"]],

];

$opcoes_menu = $permissoes[$id_perfil];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuário</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<body>

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

    <center><h2>Lista de Fornecedor</h2></center>

    <!-- FORMULARIO PARA BUSCAR USUARIOS -->

    <form action="buscar_fornecedor.php" method="POST">
        <label for="busca">Digite o ID ou NOME(opcional)</label>
        <input type="text" id="busca" name="busca">
        <button type="submit" class="btn btn-primary">Pesquisar</button>
    </form>

    <?php if(!empty($fornecedores)):?>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <center><table border="1" class ="table table-striped"> 
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Contato</th>
                <th>Endereço</th>
                <th>Ações</th>
            </tr>

            <?php foreach($fornecedores as $fornecedor): ?>
                <tr>
                    <td><?=htmlspecialchars($fornecedor['id_fornecedor']) ?></td>
                    <td><?=htmlspecialchars($fornecedor['nome_fornecedor']) ?></td> 
                    <td><?=htmlspecialchars($fornecedor['email']) ?></td>
                    <td><?=htmlspecialchars($fornecedor['contato']) ?></td>
                    <td><?=htmlspecialchars($fornecedor['endereco']) ?></td>
                    <td>
                        <a class="btn btn-outline-warning"href="alterar_fornecedor.php?id=<?=htmlspecialchars($fornecedor['id_fornecedor'])?>">Alterar</a>
                        <a class="btn btn-outline-danger" href="excluir_fornecedor.php?id=<?=htmlspecialchars($fornecedor['id_fornecedor'])?>"onclick="return confirm('Tem certeza que deseja excluir esse fornecedor?')">Excluir</a>
                    </td> 
                </tr>
            <?php endforeach; ?>
        </table></center>
    <?php else: ?>
        <center><p> Nenhum Fornecedor encontrado.</p></center>
    <?php endif; ?>
    <br>
    <center><a href="principal.php" class="btn btn-primary" >Voltar</a></center>

</body>
</html>