<?php
// src/paths.php

if (!isset($baseDir)) {
    $baseDir = dirname(__DIR__);
}

$paths = [
    'root' => $baseDir,
    'dirs' => [],
    'files' => []
];

// Diretórios principais
$paths['dirs']['public']    = $baseDir . '/public';
$paths['dirs']['src']       = $baseDir . '/src';
$paths['dirs']['storage']   = $baseDir . '/storage';
$paths['dirs']['tools']     = $baseDir . '/tools';

// Subdiretórios derivados (organização visual e lógica)
$paths['dirs']['assets']    = $paths['dirs']['public'] . '/assets';
$paths['dirs']['css']       = $paths['dirs']['assets'] . '/css';
$paths['dirs']['icons']     = $paths['dirs']['assets'] . '/icons';
$paths['dirs']['views']     = $paths['dirs']['src'] . '/views';
$paths['dirs']['pages']     = $paths['dirs']['views'] . '/pages';
$paths['dirs']['partials']  = $paths['dirs']['views'] . '/partials';
$paths['dirs']['components']= $paths['dirs']['views'] . '/components';
$paths['dirs']['handlers']  = $paths['dirs']['src'] . '/handlers';

// Garante a existência de todos os diretórios do mapa automaticamente
foreach ($paths['dirs'] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Arquivos unificados e mapeados diretamente
$paths['files'] = [
    'index'    => $paths['dirs']['public'] . '/index.php',
    'utils'    => $paths['dirs']['src'] . '/utils.php',
    'database' => $paths['dirs']['src'] . '/Database.php',
    'merchant' => $paths['dirs']['src'] . '/Merchant.php',
    'setting'  => $paths['dirs']['src'] . '/Setting.php',
    'auth'     => $paths['dirs']['src'] . '/Auth.php',
    'router'   => $paths['dirs']['src'] . '/router.php',
    'sqlite'   => $paths['dirs']['storage'] . '/database.sqlite',
];

return $paths;