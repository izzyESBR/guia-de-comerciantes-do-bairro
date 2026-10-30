<?php
// src/handlers/login.php
/** @var array $paths */

if ($auth->check()) {
    header('Location: index.php?page=list');
    exit;
}

// Processamento do formulário de login (quando o usuário enviar a senha via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    if ($auth->login($password)) {
        header('Location: index.php?page=list');
    } else {
        header('Location: index.php?page=login');
    }
    exit;
}

// Define os estilos exclusivos desta página
$pageStyles = [
    'pico.min',
    'style',
    'login'
];