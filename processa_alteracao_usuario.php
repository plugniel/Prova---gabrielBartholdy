<?php
session_start();
require_once 'conexao.php';

// VERFICIA SE O USUARIO TEM PERMISSÃO DE ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $id_perfil = $_POST['id_perfil'];
    $nova_senha = !empty($_POST['nova_senha']) ? password_hash($_POST['nova_senha'], PASSWORD_DEFAULT) : null;
    //ATUALIZA OS DADOS DO USUARIO
    if ($nova_senha) {
        $sql = "UPDATE usuario SET nome = :nome, email = :email, id_perfil = :id_perfil, senha = :senha WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $nova_senha);
    } else {
        $sql = "UPDATE usuario SET nome = :nome, email = :email, id_perfil = :id_perfil WHERE id_usuario = :id";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id_perfil', $id_perfil);
    $stmt->bindParam(':id', $id_usuario);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário atualizado com sucesso!');window.location.href='buscar_usuario.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar o usuário!');window.location.href='alterar_usuario.php?id=$usuario';</script>";
    }
}

    
?>