<?php
// src/views/pages/setup.php
/** @var array $paths */
/** @var string $page */
?>
<main class="container" id="<?=  htmlspecialchars($page) ?>">
    <article>
        <h1>⚙️ Configuração Inicial</h1>
        
        <form action="index.php?page=setup" method="POST">
            <!-- Título da Página -->
            <label for="homepage_title">Título do Guia / Página</label>
            <input type="text" id="homepage_title" name="homepage_title" placeholder="Ex: Guia Comercial do Bairro" value="Guia Comercial do Bairro" required>

            <!-- Senha de Administração -->
            <label for="password">Senha de Administrador</label>
            <input type="password" id="password" name="password" placeholder="Digite uma senha segura" required>

            <!-- Confirmação de Senha -->
            <label for="password_confirm">Confirme a Senha</label>
            <input type="password" id="password_confirm" name="password_confirm" placeholder="Repita a senha escolhida" required>

            <!-- Botão de submissão -->
            <button type="submit" class="btn-pastel-save">Salvar e Concluir</button>
        </form>
    </article>
</main>