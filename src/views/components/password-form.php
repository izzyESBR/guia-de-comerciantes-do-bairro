<?php
// src/views/components/password-form.php
?>
<section>
    <h2>🔑 Alterar Senha de Administrador</h2>
    <form action="index.php?page=admin&action=update_password" method="POST">
        <label for="current_password">Senha Atual</label>
        <input type="password" id="current_password" name="current_password" placeholder="Digite a senha atual" required>

        <label for="new_password">Nova Senha</label>
        <input type="password" id="new_password" name="new_password" placeholder="Digite a nova senha" required>
        
        <label for="confirm_password">Confirme a Senha</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirme a nova senha" required>

        <button type="submit" class="btn-pastel-admin">Atualizar Senha</button>
    </form>
</section>