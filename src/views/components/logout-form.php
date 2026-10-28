<?php
// src/views/components/logout-form.php
?>
<section class="admin-section" style="border-color: #fca5a5;">
    <h2 style="color: #e11d48;">🚪 Encerrar Sessão (Logout)</h2>
    <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 1rem;">Encerra sua sessão atual de administrador com segurança.</p>
    
    <form action="index.php?page=admin&action=logout" method="POST" style="margin: 0;">
        <button type="submit" class="btn-danger-pastel">Sair do Painel</button>
    </form>
</section>