<?php
// src/views/pages/list.php
/** @var int $totalTabs */
/** @var int $tab */
/** @var array $tabs */
/** @var array $prevParams */
/** @var array $nextParams */
?>

<?php if ($totalTabs > 1): ?>
    <nav class="pagination-container" aria-label="Paginação">
        <ul>
            <!-- Botão Página Anterior -->
            <?php if ($tab > 1): ?>
                <li><a href="index.php?<?= http_build_query($prevParams) ?>" class="outline green">Anterior</a></li>
            <?php endif; ?>

            <!-- Números das Páginas -->
            <?php foreach ($tabs as $item): ?>
                <li>
                    <a href="<?= $item['url'] ?>" class="<?= $item['linkClass'] ?>">
                        <?= $item['tab'] ?>
                    </a>
                </li>
            <?php endforeach; ?>

            <!-- Botão Próxima Página -->
            <?php if ($tab < $totalTabs): ?>
                <li><a href="index.php?<?= http_build_query($nextParams) ?>" class="outline green">Próxima</a></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>