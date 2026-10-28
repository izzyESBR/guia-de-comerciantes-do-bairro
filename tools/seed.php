<?php
// tools/seed.php
/** @var array $paths */
// Executado via terminal: php run seed <nome-do-arquivo.csv>

// 1. Verifica se o nome do arquivo CSV foi passado como argumento
$csvFilename = $argv[2] ?? null;

if (!$csvFilename) {
    echo "Erro: Você precisa informar o nome do arquivo CSV.\n";
    echo "Exemplo de uso: php run seed comerciantes_guia\n";
    exit(1);
}

// 2. Localiza o arquivo dentro da pasta storage/ (com ou sem .csv)
$csvPath = $paths['dirs']['storage'] . '/' . $csvFilename;

if (!file_exists($csvPath) && file_exists($csvPath . '.csv')) {
    $csvPath = $csvPath . '.csv';
}

if (!file_exists($csvPath)) {
    echo "Erro: O arquivo '{$csvFilename}' não foi encontrado na pasta storage/.\n";
    exit(1);
}

echo "📂 Lendo arquivo de dados: " . basename($csvPath) . "\n";

// 3. Carrega o Model Merchant (que já carrega a Database automaticamente)
require_once $paths['files']['merchant'];

// 4. Abre o arquivo CSV com Guard Clause (falha rápido se não conseguir)
$handle = fopen($csvPath, 'r');
if ($handle === false) {
    echo "Erro ao abrir o arquivo CSV para leitura.\n";
    exit(1);
}

// Pula a linha do cabeçalho
fgetcsv($handle, 1000, ',', '"', '\\');

$merchantsBatch = [];

while (($data = fgetcsv($handle, 1000, ',', '"', '\\')) !== FALSE) {
    if (count($data) < 7) {
        continue; // Ignora linhas malformadas
    }
    
    // Mapeia os campos para o formato esperado pelo Model
    $merchantsBatch[] = [
        'category'     => trim($data[0]),
        'sub_category' => trim($data[1]),
        'name'         => trim($data[2]),
        'whatsapp'     => trim($data[3]),
        'instagram'    => trim($data[4]),
        'website'      => trim($data[5]),
        'observations' => trim($data[6] ?? '')
    ];
}

fclose($handle);

if (empty($merchantsBatch)) {
    echo "Aviso: Nenhum registro válido foi encontrado no arquivo CSV.\n";
    exit(0);
}

// 5. Envia o lote inteiro para a classe Merchant processar com transação atômica
try {
    $rowCount = Merchant::createBatch($merchantsBatch);
    echo "Sucesso! {$rowCount} comerciantes foram importados via Merchant::createBatch().\n";
} catch (Exception $e) {
    echo "Erro durante a importação em lote: " . $e->getMessage() . "\n";
    exit(1);
}