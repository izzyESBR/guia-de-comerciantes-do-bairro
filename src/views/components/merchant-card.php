<?php
// src/views/components/merchant-card.php
/** @var array $merchant */
?>
<article class="merchant-card">
    <!-- 1. Barra no topo com a Categoria -->
    <div class="card-category-bar">
        <?= htmlspecialchars($merchant['category']) ?>
    </div>

    <div class="card-body">
        <!-- 2. Nome em Destaque -->
        <h3 class="merchant-title">
            <?= htmlspecialchars($merchant['name']) ?>
        </h3>

        <!-- 3. Subcategoria -->
        <span class="merchant-subtitle">
            <?= htmlspecialchars($merchant['sub_category']) ?>
        </span>

        <!-- 4. Descrição / Observações -->
        <?php if (!empty($merchant['observations'])): ?>
            <p class="merchant-description">
                <?= htmlspecialchars($merchant['observations']) ?>
            </p>
        <?php endif; ?>

        <!-- Informações discretas de contato no corpo do card -->
        <?php if (!empty($merchant['whatsapp']) || !empty($merchant['instagram'])): ?>
            <div class="card-meta-contacts">
                <?php if (!empty($merchant['whatsapp'])): ?>
                    <span class="meta-contact-item">
                        <span class="card-icon" style="-webkit-mask-image: url('./assets/icons/whatsapp.svg'); mask-image: url('./assets/icons/whatsapp.svg');" alt="WhatsApp"></span>
                        <?= htmlspecialchars(Database::formatPhone($merchant['whatsapp'])) ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($merchant['instagram'])): ?>
                    <span class="meta-contact-item">
                        <span class="card-icon" style="-webkit-mask-image: url('./assets/icons/whatsapp.svg'); mask-image: url('./assets/icons/instagram.svg');" alt="Instagram"></span>
                        <?= htmlspecialchars($merchant['instagram']) ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($merchant['website'])): ?>
                    <span class="meta-contact-item">
                        <span class="card-icon" style="-webkit-mask-image: url('./assets/icons/browser-chrome.svg'); mask-image: url('./assets/icons/browser-chrome.svg');" alt="Browser Chrome"></span>
                        <?= htmlspecialchars($merchant['website']) ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- 5. Rodapé com os Botões de Contato e Ícones -->
    <div class="card-footer">
        <?php if (!empty($merchant['whatsapp'])): ?>
            <a href="https://wa.me/55<?= preg_replace('/\D/', '', $merchant['whatsapp']) ?>" target="_blank" role="button" class="btn-contact btn-whatsapp">
                <img src="./assets/icons/whatsapp.svg" alt="WhatsApp" class="btn-icon">
            </a>
        <?php endif; ?>

        <?php if (!empty($merchant['instagram'])): ?>
            <a href="https://instagram.com/<?= ltrim($merchant['instagram'], '@') ?>" target="_blank" role="button" class="btn-contact btn-instagram">
                <img src="./assets/icons/instagram.svg" alt="Instagram" class="btn-icon">
            </a>
        <?php endif; ?>

        <?php if (!empty($merchant['website'])): ?>
            <a href="https://<?= htmlspecialchars($merchant['website']) ?>" target="_blank" role="button" class="btn-contact">
                <img src="./assets/icons/browser-chrome.svg" alt="Browser Chrome" class="btn-icon">
            </a>
        <?php endif; ?>
    </div>
</article>