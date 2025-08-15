<?php
session_start();
require_once 'conexao.php';

// verifica se o usuario tem permissao 
if ($_SESSION['perfil']!= 1) {
    echo "Acesso negado. ";>;
    exit();
}

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $nome = $_POSTN['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $id_perfil = $_POST['id_perfil'];
    $sql = "INSERT INTO usuario (nome, email, senha, id_perfil) VALUES (:nome, :email, :senha, :id_perfil)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':id_perfil', $id_perfil);
}
?>