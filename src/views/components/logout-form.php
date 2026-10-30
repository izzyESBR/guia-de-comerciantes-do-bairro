<?php
// src/views/components/logout-form.php
?>
<section class="cancel">
    <h2>🚪 Encerrar Sessão (Logout)</h2>
    <p>Encerra sua sessão atual de administrador com segurança.</p>
    
    <form action="index.php?page=admin&action=logout" method="POST" style="margin: 0;">
        <button type="submit" class="btn-danger-pastel">Sair do Painel</button>
    </form>
</section>