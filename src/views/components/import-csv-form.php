<?php
// src/views/components/import-csv-form.php
?>
<section>
    <h2>📁 Importar Registros via CSV</h2>
    <form action="index.php?page=admin&action=import_csv" method="POST" enctype="multipart/form-data">
        <label for="csv_file">Selecione o arquivo CSV</label>
        <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
        <button type="submit" class="btn-pastel-admin">Importar CSV</button>
    </form>
</section>