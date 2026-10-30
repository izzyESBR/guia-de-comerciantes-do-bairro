<?php
// src/handlers/admin.php
/** @var array $paths */
/** @var Auth $auth */

// Garante que apenas administradores autenticados acessem o painel
if (!$auth->check()) {
    header('Location: index.php?page=login');
    exit;
}

require_once $paths['files']['setting'];
require_once $paths['files']['merchant'];

// Processamento das ações via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';

    if ($action === 'update_title') {
        $newTitle = trim($_POST['homepage_title'] ?? '');

        if ($newTitle !== '') {
            Setting::set('homepage_title', $newTitle);
        }
        
        // Redireciona de volta para o painel com sucesso
        header('Location: index.php?page=admin');
        exit;
    }

    if ($action === 'logout') {
        $auth->logout();
        header('Location: index.php?page=list');
        exit;
    }

    if ($action === 'import_csv') {
        
        // Verifica se o arquivo foi enviado sem erros
        if (!isset($_FILES['csv_file']) OR $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            // Redireciona de volta para o painel
            header('Location: index.php?page=admin');
            exit;
        }

        $fileTmpPath = $_FILES['csv_file']['tmp_name'];
        $fileName = $_FILES['csv_file']['name'];
        
        // 1. Validação por extensão
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // 2. Validação por tipo MIME real do arquivo usando Fileinfo
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($fileTmpPath);
        
        // Tipos MIME comuns aceitos para CSV (incluindo variações de texto/csv, plain, etc.)
        $allowedMimeTypes = ['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel'];
        

        if ($fileExtension !== 'csv' OR !in_array($mimeType, $allowedMimeTypes, true)) {
            // Redireciona de volta para o painel
            header('Location: index.php?page=admin');
            exit;
        }
        
        // O arquivo é um CSV válido!
        // Abre o arquivo CSV enviado temporariamente
        $handle = fopen($fileTmpPath, 'r');
        
        if ($handle === false) {
            // Redireciona de volta para o painel
            header('Location: index.php?page=admin');
            exit;
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
            // Redireciona de volta para o painel
            header('Location: index.php?page=admin');
            exit;
        }

        // Se encontrou registros válidos, limpa a tabela e insere o novo lote
        try {
            $db = Database::getConnection();
            
            // Remove todos os registros antigos mantendo a estrutura da tabela pronta para o novo lote
            $db->exec("DELETE FROM merchants");

            // Insere os dados em lote de forma atômica
            Merchant::createBatch($merchantsBatch);
        } catch (Exception $e) {
            // Em um cenário real, você pode opcionalmente salvar uma mensagem de erro na sessão
        }
        
        // Redireciona de volta para o painel
        header('Location: index.php?page=admin');
        exit;
    }

    if ($action === 'update_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validação fail-fast: se os campos estiverem vazios, redireciona
        if (trim($currentPassword) === '' || trim($newPassword) === '') {
            header('Location: index.php?page=admin');
            exit;
        }

        // Validação fail-fast: se o usuário digitou a senha correta duas vezes
        if ($newPassword !== $confirmPassword) {
            header('Location: index.php?page=admin');
            exit;
        }

        // Obtém a hash atual armazenada nas configurações
        $currentHash = Setting::get('admin_password_hash');

        // Valida se a senha atual bate com a hash armazenada
        if (!$currentHash || !password_verify($currentPassword, $currentHash)) {
            // Senha atual incorreta
            header('Location: index.php?page=admin');
            exit;
        }

        // Gera a nova hash com segurança e salva nas configurações
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        Setting::set('admin_password_hash', $newHash);

        // Redireciona de volta para o painel com sucesso
        header('Location: index.php?page=admin');
        exit;
    }
}

// Define os estilos exclusivos desta página
$pageStyles = [
    'pico.min',
    'style',
    'admin'
];