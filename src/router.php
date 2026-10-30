<?php
// src/router.php
/** @var array $paths */

// Carrega as dependências essenciais de infraestrutura primeiro
require_once $paths['files']['auth'];
require_once $paths['files']['setting'];

// Verifica se o setup inicial já foi concluído (se existe senha no SQLite) e inicio Auth
$isSetupDone = Setting::isSetupCompleted();
$auth = new Auth();

// Define um título global padrão buscado nas configurações (com fallback)
$pageTitle = Setting::get('homepage_title', 'Guia Comercial');

// Captura a página e define 'list' como padrão
$page = $_GET['page'] ?? 'list';

// Se o setup não foi feito, força o redirecionamento/exibição da página de setup
if (!$isSetupDone) {
    $page = 'setup';
}

// Garante que a tab seja sempre um número inteiro, com valor mínimo de 1
$tab = isset($_GET['tab']) ? max(1, (int) $_GET['tab']) : 1;

// Whitelist de páginas permitidas
$allowedPages = [
    'list'   => 'list',
    'setup'  => 'setup',
    'login'  => 'login',
    'admin'  => 'admin',
    'new'    => 'new',
    'edit'   => 'edit',
];

// Valida se a página solicitada existe na whitelist
if (!array_key_exists($page, $allowedPages)) {
    $page = 'list';
}

// Carrega o handler correspondente automaticamente por convenção
$handlerFile = $paths['dirs']['handlers'].'/'.$page.'.php';
$pageFile  = $paths['dirs']['pages'].'/'.$page.'.php';
if (file_exists($handlerFile)) {
    require_once $handlerFile;
}