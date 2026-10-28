<div class="search-form-container">
    <form action="index.php" method="GET">
        <fieldset role="group">
            <input type="text" name="search" placeholder="O que você procura? (Nome, categoria, etc.)" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit">
                <span class="search-btn-icon" style="-webkit-mask-image: url('./assets/icons/search.svg'); mask-image: url('./assets/icons/search.svg');" alt="Search"></span>
            </button>
        </fieldset>
    </form>
</div>