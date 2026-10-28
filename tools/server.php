<?php
// tools/server.php

$host = '127.0.0.1';
$startPort = 8000;
$maxAttempts = 999;
$port = null;

/**
 * Verifica se uma porta está livre tentando abrir uma conexão socket.
 */
function isPortAvailable(string $host, int $port): bool {
    $connection = @fsockopen($host, $port, $errno, $errstr, 0.2);
    if (is_resource($connection)) {
        fclose($connection);
        return false; // A porta já está em uso
    }
    return true; // A porta está livre
}

// Procura a primeira porta livre a partir de 8000
for ($i = 0; $i < $maxAttempts; $i++) {
    $testPort = $startPort + $i;
    if (isPortAvailable($host, $testPort)) {
        $port = $testPort;
        break;
    }
}

if ($port === null) {
    fwrite(STDERR, "Erro: Nenhuma porta disponível encontrada entre {$startPort} e " . ($startPort + $maxAttempts - 1) . ".\n");
    exit(1);
}

$publicDir = __DIR__ . '/../public';

if (!is_dir($publicDir)) {
    fwrite(STDERR, "Erro: O diretório 'public' não foi encontrado em: {$publicDir}\n");
    exit(1);
}

// Exibe informações amigáveis no terminal antes de subir
echo "===================================================\n";
echo "  🚀 Iniciando Servidor de Desenvolvimento PHP\n";
echo "===================================================\n";
echo "  • Endereço:    http://{$host}:{$port}\n";
echo "  • Raiz (Root): {$publicDir}\n";
echo "  • Status:      Rodando (Pressione Ctrl+C para parar)\n";
echo "===================================================\n\n";

// Monta e executa o comando do servidor embutido do PHP apontando para a pasta public
$command = sprintf(
    'php -S %s:%d -t %s',
    $host,
    $port,
    escapeshellarg($publicDir)
);

passthru($command);