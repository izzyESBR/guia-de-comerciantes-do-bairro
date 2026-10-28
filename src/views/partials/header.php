<?php
// src/views/partials/header.php
?>
<header class="container">
    <nav>
        <ul>
            <li><a href="index.php?page=list" class="green">Início</a></li>
            <?php if ($auth->check()) : ?>
                <li><a href="index.php?page=admin" class="green">Painel Admin</a></li>
            <?php else: ?>
                <li><a href="index.php?page=login" class="green">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>