<?php
// src/utils.php

/**
 * Renderiza dinamicamente as tags <link rel="stylesheet">
 */
function render_css(array $stylesheets): void {
    foreach ($stylesheets as $css) {
        $href = "./assets/css/" . ltrim($css, '/');
        echo '<link rel="stylesheet" href="' . htmlspecialchars($href) . '.css">' . "\n    ";
    }
}