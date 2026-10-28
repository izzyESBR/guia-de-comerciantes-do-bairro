<?php
// src/views/partials/footer.php
/** @var string $pageTitle */
?>
<footer>
    <hr>
    <p style="text-align: center; font-size: 0.9rem; color: #666;">
        &copy; <?= date('Y') ?> <i><?= htmlspecialchars($pageTitle) ?></i>. Todos os direitos reservados.
    </p>
</footer>