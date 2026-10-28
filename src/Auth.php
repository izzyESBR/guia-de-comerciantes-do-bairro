<?php
// src/Auth.php

$paths = require __DIR__ . '/paths.php';
require_once $paths['files']['database'];

class Auth {
    private bool $isAuthenticated = false;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->isAuthenticated = $_SESSION['logged_in'] ?? false;
    }

    /**
     * Tenta autenticar o administrador comparando a senha informada com o hash do SQLite
     */
    public function login(string $password): bool {
        // Busca o hash da senha diretamente na tabela settings do banco de dados
        $passwordHash = Database::getSetting('admin_password_hash');

        if ($passwordHash && password_verify($password, $passwordHash)) {
            $_SESSION['logged_in'] = true;
            $this->isAuthenticated = true;
            return true;
        }

        return false;
    }

    /**
     * Encerra a sessão do administrador
     */
    public function logout(): void {
        $_SESSION['logged_in'] = false;
        $this->isAuthenticated = false;
        unset($_SESSION['logged_in']);
        session_destroy();
    }

    /**
     * Verifica se o usuário atual está autenticado
     */
    public function check(): bool {
        return $this->isAuthenticated;
    }
}