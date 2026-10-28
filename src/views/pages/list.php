<?php
// src/views/pages/list.php
/** @var array $paths */
/** @var array $merchants */
/** @var string $pageTitle */
?>
<!DOCTYPE html>

<html lang="pt-BR">

    <!-- Head -->
    <?php require_once $paths['dirs']['partials'] . '/head.php'; ?>

    <body>
        
        <?php require_once $paths['dirs']['partials'] . '/header.php'; ?>

        <main class="container">
            <h1><?= htmlspecialchars($pageTitle) ?></h1>
            
            <!-- Formulário de Busca -->
            <?php require $paths['dirs']['components'] . '/search-form.php'; ?>

            <!-- Índice de Categorias -->
            <?php require $paths['dirs']['components'] . '/category-toc.php'; ?>

            <!-- Renderiza a Grade de Comerciantes -->
            <?php require $paths['dirs']['components'] . '/merchant-grid.php'; ?>
            
            <!-- Renderiza a Paginação -->
            <?php require $paths['dirs']['components'] . '/grid-pagination.php'; ?>
        </main>

        <!-- Footer -->
        <?php require $paths['dirs']['partials'] . '/footer.php'; ?>

    </body>
</html>