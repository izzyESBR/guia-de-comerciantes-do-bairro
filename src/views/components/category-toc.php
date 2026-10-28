<?php
// src/views/components/category-toc.php
/** @var array $categories */

$currentCategory = $_GET['category'] ?? '';
?>

<nav class="category-toc">
    <!-- Opção Ver Todos -->
    <a href="index.php" class="toc-item <?= empty($currentCategory) && !isset($_GET['search']) ? 'active' : '' ?>">
        🌐 Ver Todos
    </a>

    <!-- Lista de Categorias vindas do banco -->
    <?php foreach ($categories as $category): ?>
        <a href="index.php?category=<?= urlencode($category) ?>" 
           class="toc-item <?= ($currentCategory === $category) ? 'active' : '' ?>">
            <?= htmlspecialchars($category) ?>
        </a>
    <?php endforeach; ?>
</nav>