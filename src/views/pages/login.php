<?php
// src/views/pages/login.php
/** @var array $paths */
/** @var string $page */
?>
<main class="container" id="<?=  htmlspecialchars($page) ?>">
    <article>
        <h1>🔐 Acesso Restrito</h1>
        
        <form action="index.php?page=login" method="POST">
            <!-- Senha -->
            <label for="password">Senha de Administrador</label>
            <input type="password" id="password" name="password" placeholder="Digite sua senha" required autofocus>

            <!-- Botão de submissão -->
            <button type="submit" class="btn-pastel-login">Entrar</button>
            
            <!-- Botão para cancelar e retornar à home -->
            <a href="index.php?page=list" role="button" class="secondary outline btn-cancel">Cancelar</a>
        </form>
    </article>
</main>