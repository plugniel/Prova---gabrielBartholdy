<?php
session_start();
require_once 'conexao.php';

// Garante que o usuário esteja logado
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>alert('Acesso negado.');window.location.href='login.php';</script>";
    exit();
    header('Location: login.php');
    exit();
}