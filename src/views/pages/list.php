<?php
// src/views/pages/list.php
/** @var array $paths */
/** @var array $merchants */
/** @var string $pageTitle */
/** @var string $page */
?>
<main class="container" id="<?=  htmlspecialchars($page) ?>">
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
