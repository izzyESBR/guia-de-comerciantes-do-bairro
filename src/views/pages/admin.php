<?php
// src/views/pages/admin.php
/** @var array $paths */
/** @var string $page */
?>
<main class="container" id="<?=  htmlspecialchars($page) ?>">
    <h1>⚙️ Painel de Administração</h1>
    <p style="color: #64748b; margin-bottom: 2rem;">Gerencie as configurações gerais e dados do diretório comercial.</p>

    <!-- Formulário para Alterar o Nome do Site -->
    <?php require_once $paths['dirs']['components'] . '/site-title-form.php'; ?>

    <!-- Formulário para Enviar Arquivo CSV -->
    <?php require_once $paths['dirs']['components'] . '/import-csv-form.php'; ?>
    
    <!-- Formulário para Alterar a Senha -->
    <?php require_once $paths['dirs']['components'] . '/password-form.php'; ?>
    
    <!-- Ação de Desligar / Sair da Conta (Logout) -->
    <?php require_once $paths['dirs']['components'] . '/logout-form.php'; ?>
    
</main>