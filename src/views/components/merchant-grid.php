<?php
// src/views/partials/merchant-grid.php
/** @var array $paths */
/** @var array $merchants */
?>
<?php if (empty($merchants)): ?>
    <article>
        <p style="text-align: center; margin: 0;">Nenhum comerciante encontrado para a sua busca.</p>
    </article>
<?php else: ?>
    <div class="merchant-grid">
        <?php foreach ($merchants as $merchant): ?>
            <?php 
            // Inclui o card individual repassando o escopo da variável $m
            require $paths['dirs']['components'] . '/merchant-card.php'; 
            ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>