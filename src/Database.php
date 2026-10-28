<?php
// src/Database.php

class Database {
    private static ?PDO $instance = null;

    /**
     * Remove acentos e converte para minúsculas para padronização de busca
     */
    public static function cleanString(string $string, string $type = 'generic'): string {
        $string = mb_strtolower($string, 'UTF-8');
        $map = [
            'á'=>'a', 'à'=>'a', 'ã'=>'a', 'â'=>'a', 'ä'=>'a',
            'é'=>'e', 'è'=>'e', 'ê'=>'e', 'ë'=>'e',
            'í'=>'i', 'ì'=>'i', 'î'=>'i', 'ï'=>'i',
            'ó'=>'o', 'ò'=>'o', 'õ'=>'o', 'ô'=>'o', 'ö'=>'o',
            'ú'=>'u', 'ù'=>'u', 'û'=>'u', 'ü'=>'u',
            'ç'=>'c', 'ñ'=>'n'
        ];
        $string = strtr($string, $map);

        switch($type) {
            default:
            case 'generic':
                $string = $string;
                break;
            case 'whatsapp':
                $string = preg_replace('/\D/', '', $string);
                break;
            case 'instagram':
                $string = trim(str_replace(' ', '', $string));
                $string = ltrim($string, '@');
                break;
            case 'url':
                $string = trim($string);
                $string = preg_replace('#^https?://#i', '', $string);
                $string = preg_replace('#^www\.#i', '', $string);
                $string = rtrim($string, '/');
                break;
        }

        return $string;
    }

    /**
     * Formata um número de telefone/WhatsApp para o padrão brasileiro: (00) 00000-0000 ou (00) 0000-0000
     */
    public static function formatPhone(string $phone): string {
        // Remove tudo o que não for dígito
        $digits = preg_replace('/\D/', '', $phone);
        $length = strlen($digits);

        if ($length === 11) {
            // Celular: (XX) 9XXXX-XXXX
            return sprintf('(%s) %s-%s', substr($digits, 0, 2), substr($digits, 2, 5), substr($digits, 7));
        } elseif ($length === 10) {
            // Fixo: (XX) XXXX-XXXX
            return sprintf('(%s) %s-%s', substr($digits, 0, 2), substr($digits, 2, 4), substr($digits, 6));
        }

        // Se não tiver 10 ou 11 dígitos, retorna o original limpo ou cru
        return $phone;
    }

    /**
     * Retorna a instância única da conexão PDO (Singleton)
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $paths = require __DIR__ . '/paths.php';
            $dbPath = $paths['files']['sqlite'];

            $pdo = new PDO('sqlite:' . $dbPath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Ativa o modo WAL para melhor performance e concorrência
            $pdo->exec('PRAGMA journal_mode = WAL;');
            
            self::$instance = $pdo;

            // Garante a criação/atualização das tabelas ao conectar pela primeira vez
            self::createTables();
        }

        return self::$instance;
    }

    /**
     * Cria as tabelas do sistema se elas não existirem
     */
    private static function createTables(): void {
        // Tabela de comerciantes com as colunas oficiais e as colunas _clean para busca tolerante
        self::$instance->exec("CREATE TABLE IF NOT EXISTS merchants (
            id TEXT PRIMARY KEY,
            category TEXT NOT NULL,
            sub_category TEXT NOT NULL,
            name TEXT NOT NULL,
            whatsapp TEXT,
            instagram TEXT,
            website TEXT,
            observations TEXT,
            _clean_category TEXT NOT NULL,
            _clean_sub_category TEXT NOT NULL,
            _clean_name TEXT NOT NULL,
            _clean_observations TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Tabela de configurações globais do sistema
        self::$instance->exec("CREATE TABLE IF NOT EXISTS settings (
            key TEXT PRIMARY KEY,
            value TEXT NOT NULL
        )");
    }

    /**
     * Busca o valor de uma configuração pelo nome da chave
     */
    public static function getSetting(string$key, string $default = ''): string {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT value FROM settings WHERE key = :key");
        $stmt->execute([':key' => $key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['value'] : $default;
    }

    /**
     * Salva ou atualiza o valor de uma configuração
     */
    public static function setSetting(string $key, string $value): bool {
        $db = self::getConnection();
        $stmt = $db->prepare("INSERT INTO settings (key, value) VALUES (:key, :value) 
                              ON CONFLICT(key) DO UPDATE SET value = :value");
        return $stmt->execute([':key' => $key, ':value' => $value]);
    }
}