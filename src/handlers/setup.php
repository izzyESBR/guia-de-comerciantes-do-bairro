<?php
// src/handlers/setup.php
/** @var array $paths */

// Se o setup já foi concluído, impede o re-acesso e manda de volta para a listagem
if (Setting::isSetupCompleted()) {
    header('Location: index.php?page=list');
    exit;
}

// Processamento do formulário de setup (quando o usuário enviar a senha via POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $homepage_title = $_POST['homepage_title'] ?? '';

    if ($password !== $password_confirm) {
        header('Location: index.php?page=setup');
        exit;
    }
    
    if (trim($password) !== '' and trim($homepage_title) !== '') {
        // Salva o hash da senha e o título nas configurações
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        Setting::set('admin_password_hash', $hashedPassword);
        Setting::set('homepage_title', $homepage_title);
        
        // Redireciona para a listagem após concluir
        header('Location: index.php?page=list');
        exit;
    }
}

// Define os estilos exclusivos desta página
$pageStyles = [
    'pico.min',
    'style',
    'setup'
];

// Carrega a View da página de setup
require_once $paths['dirs']['pages'] . '/setup.php';