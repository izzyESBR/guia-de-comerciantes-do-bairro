<?php
// src/views/components/site-title-form.php
?>
<section class="admin-section">
    <h2>🌐 Alterar Título do Site</h2>
    <form action="index.php?page=admin&action=update_title" method="POST">
        <label for="homepage_title">Novo Título da Página</label>
        <input type="text" id="homepage_title" name="homepage_title" placeholder="Ex: Guia Comercial Serra" required>

        <button type="submit" class="btn-pastel-admin">Salvar Título</button>
    </form>
</section>