<?php
// public/index.php
/** @var string $pageFile */

// Carrega os caminhos globais e o roteador
$paths = require_once __DIR__ . '/../src/paths.php';
require_once $paths['files']['utils'];
require_once $paths['files']['auth'];
require_once $paths['files']['router'];

?>

<!DOCTYPE html>

<html lang="pt-BR">

    <!-- Head -->
    <?php require_once $paths['dirs']['partials'] . '/head.php'; ?>

    <body>
        
        <!-- Header nav -->
        <?php require_once $paths['dirs']['partials'] . '/header.php'; ?>

        <!-- Page body -->
        <?php if(file_exists($pageFile)) { require $pageFile;} ?>

        <!-- Footer -->
        <?php require $paths['dirs']['partials'] . '/footer.php'; ?>

    </body>
</html>