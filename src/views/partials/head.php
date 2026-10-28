<?php
// src/views/partials/head.php
/** @var string $pageTitle */
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- Renderiza dinamicamente os estilos injetados pelo handler -->
    <?php if (!empty($pageStyles)) render_css($pageStyles); ?>

</head>