<?php
// src/Setting.php

$paths = require __DIR__ . '/paths.php';
require_once $paths['files']['database'];

class Setting {
    
    /**
     * Retorna o valor de uma configuração pelo nome da chave
     */
    public static function get(string $key, ?string $default = null): ?string {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT value FROM settings WHERE key = :key");
        $stmt->execute([':key' => $key]);
        $result = $stmt->fetchColumn();

        return $result !== false ? $result : $default;
    }

    /**
     * Define ou atualiza uma configuração (Upsert)
     */
    public static function set(string $key, string $value): void {
        $db = Database::getConnection();

        $stmt = $db->prepare("
            INSERT INTO settings (key, value) VALUES (:key, :value)
            ON CONFLICT(key) DO UPDATE SET value = :value
        ");
        $stmt->execute([
            ':key' => $key,
            ':value' => $value
        ]);
    }

    /**
     * Verifica se o setup inicial já foi concluído
     */
    public static function isSetupCompleted(): bool {
        return !empty(self::get('admin_password_hash'));
    }
}