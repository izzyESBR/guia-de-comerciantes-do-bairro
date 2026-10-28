<?php
// src/Merchant.php

$paths = require __DIR__ . '/paths.php';
require_once $paths['files']['database'];

class Merchant {
    
    /**
     * Cadastra um único comerciante no banco de dados, reaproveitando a lógica de lote
     */
    public static function create(array $merchant): bool {
        $count = self::createBatch([$merchant]);
        return $count > 0;
    }

    /**
     * Realiza a importação em lote de comerciantes usando Transações SQLite (Atomicidade e Performance)
     */
    public static function createBatch(array $merchants): int {
        $db = Database::getConnection();
        $count = 0;

        try {
            $db->beginTransaction();

            $sql = "INSERT INTO merchants (id, category, sub_category, name, whatsapp, instagram, website, observations, _clean_category, _clean_sub_category, _clean_name, _clean_observations) 
                    VALUES (:id, :category, :sub_category, :name, :whatsapp, :instagram, :website, :observations, :_clean_category, :_clean_sub_category, :_clean_name, :_clean_observations)";
            
            $stmt = $db->prepare($sql);

            foreach ($merchants as $merchant) {
                $stmt->execute([
                    ':id'                  => $merchant['id'] ?? uniqid(),
                    ':category'            => $merchant['category'],
                    ':sub_category'        => $merchant['sub_category'],
                    ':name'                => $merchant['name'],
                    ':whatsapp'            => Database::cleanString($merchant['whatsapp'] ?? '', 'whatsapp'),
                    ':instagram'           => Database::cleanString($merchant['instagram'] ?? '', 'instagram'),
                    ':website'             => Database::cleanString($merchant['website'] ?? '', 'url'),
                    ':observations'        => $merchant['observations'] ?? null,
                    ':_clean_category'     => Database::cleanString($merchant['category']),
                    ':_clean_sub_category' => Database::cleanString($merchant['sub_category']),
                    ':_clean_name'         => Database::cleanString($merchant['name']),
                    ':_clean_observations' => Database::cleanString($merchant['observations'] ?? ''),
                ]);
                $count++;
            }

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }

        return $count;
    }

    /**
     * Atualiza os dados de um comerciante existente pelo ID
     */
    public static function update(string $id, array $merchant): bool {
        $db = Database::getConnection();
        
        $sql = "UPDATE merchants SET 
                category = :category, 
                sub_category = :sub_category, 
                name = :name, 
                whatsapp = :whatsapp, 
                instagram = :instagram, 
                website = :website, 
                observations = :observations,
                _clean_category = :_clean_category,
                _clean_sub_category = :_clean_sub_category,
                _clean_name = :_clean_name,
                _clean_observations = :_clean_observations
                WHERE id = :id";
        
        $stmt = $db->prepare($sql);
        
        return $stmt->execute([
            ':id'                  => $id,
            ':category'            => $merchant['category'],
            ':sub_category'        => $merchant['sub_category'],
            ':name'                => $merchant['name'],
            ':whatsapp'            => Database::cleanString($merchant['whatsapp'] ?? '', 'whatsapp'),
            ':instagram'           => Database::cleanString($merchant['instagram'] ?? '', 'instagram'),
            ':website'             => Database::cleanString($merchant['website'] ?? '', 'url'),
            ':observations'        => $merchant['observations'] ?? null,
            ':_clean_category'     => Database::cleanString($merchant['category'] ?? ''),
            ':_clean_sub_category' => Database::cleanString($merchant['sub_category'] ?? ''),
            ':_clean_name'         => Database::cleanString($merchant['name'] ?? ''),
            ':_clean_observations' => Database::cleanString($merchant['observations'] ?? ''),
        ]);
    }

    /**
     * Deleta um comerciante pelo ID
     */
    public static function delete(string $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM merchants WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Busca um comerciante específico pelo ID
     */
    public static function find(string $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, category, sub_category, name, whatsapp, instagram, website, observations, created_at FROM merchants WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result : null;
    }

    /**
     * Retorna todas as categorias únicas cadastradas, ordenadas alfabeticamente
     */
    public static function getCategories(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT DISTINCT category FROM merchants ORDER BY category ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Retorna comerciantes filtrados por parâmetros ou todos se nenhum filtro for passado
     */
    public static function search(array $filters = [], string $operator = 'AND', ?int $limit = null, int $offset = 0): array {
        $db = Database::getConnection();
        
        $sql = "SELECT id, category, sub_category, name, whatsapp, instagram, website, observations, created_at FROM merchants WHERE 1=1";
        $conditions = [];
        $params = [];

        $operator = (strtoupper($operator) === 'OR') ? 'OR' : 'AND';

        // Filtro por Nome (busca na coluna limpa e limpa o input do usuário)
        if (!empty($filters['name'])) {
            $conditions[] = "_clean_name LIKE :name";
            $params[':name'] = '%' . Database::cleanString($filters['name'] ?? '') . '%';
        }

        // Filtro por Categoria
        if (!empty($filters['category'])) {
            $conditions[] = "_clean_category LIKE :category";
            $params[':category'] = '%' . Database::cleanString($filters['category'] ?? '') . '%';
        }

        // Filtro por Subcategoria
        if (!empty($filters['sub_category'])) {
            $conditions[] = "_clean_sub_category LIKE :sub_category";
            $params[':sub_category'] = '%' . Database::cleanString($filters['sub_category'] ?? '') . '%';
        }

        // Filtro por ID (ID não precisa de limpeza de acento)
        if (!empty($filters['id'])) {
            $conditions[] = "id = :id";
            $params[':id'] = $filters['id'];
        }

        // Filtro por WhatsApp (Só aplica se sobrar algum dígito após a limpeza)
        if (!empty($filters['whatsapp'])) {
            $cleanWhatsapp = Database::cleanString($filters['whatsapp'] ?? '', 'whatsapp');
            if ($cleanWhatsapp !== '') {
                $conditions[] = "whatsapp LIKE :whatsapp";
                $params[':whatsapp'] = '%' . $cleanWhatsapp . '%';
            }
        }

        // Filtro por Instagram
        if (!empty($filters['instagram'])) {
            $conditions[] = "instagram LIKE :instagram";
            $params[':instagram'] = '%' . Database::cleanString($filters['instagram'] ?? '', 'instagram') . '%';
        }

        // Filtro por Website
        if (!empty($filters['website'])) {
            $cleanUrl = Database::cleanString($filters['website'] ?? '', 'url');
            if ($cleanUrl !== '') {
                $conditions[] = "website LIKE :website";
                $params[':website'] = '%' . $cleanUrl . '%';
            }
        }

        // Filtro por Observações
        if (!empty($filters['observations'])) {
            $conditions[] = "_clean_observations LIKE :observations";
            $params[':observations'] = '%' . Database::cleanString($filters['observations'] ?? '') . '%';
        }

        if (!empty($conditions)) {
            $sql .= " AND (" . implode(" " . $operator . " ", $conditions) . ")";
        }

        $sql .= " ORDER BY category ASC, name ASC";

        // Adiciona LIMIT e OFFSET se definidos
        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }
        if ($offset !== null) {
            $sql .= " OFFSET :offset";
        }

        $stmt = $db->prepare($sql);

        // Vincula os parâmetros de filtro
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        // Vincula limit e offset como inteiros (obrigatório no PDO com LIMIT)
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        }
        if ($offset !== null) {
            $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna o total de registros com base nos filtros (para cálculo de paginação)
     */
    public static function countSearch(array $filters = [], string $operator = 'AND'): int {
        $db = Database::getConnection();
        
        $sql = "SELECT COUNT(*) FROM merchants WHERE 1=1";
        $conditions = [];
        $params = [];
        $operator = (strtoupper($operator) === 'OR') ? 'OR' : 'AND';

        // Reaproveita os mesmos filtros do search
        if (!empty($filters['name'])) {
            $conditions[] = "_clean_name LIKE :name";
            $params[':name'] = '%' . Database::cleanString($filters['name'] ?? '') . '%';
        }
        if (!empty($filters['category'])) {
            $conditions[] = "_clean_category LIKE :category";
            $params[':category'] = '%' . Database::cleanString($filters['category'] ?? '') . '%';
        }
        if (!empty($filters['sub_category'])) {
            $conditions[] = "_clean_sub_category LIKE :sub_category";
            $params[':sub_category'] = '%' . Database::cleanString($filters['sub_category'] ?? '') . '%';
        }
        if (!empty($filters['id'])) {
            $conditions[] = "id = :id";
            $params[':id'] = $filters['id'];
        }
        if (!empty($filters['whatsapp'])) {
            $cleanWhatsapp = Database::cleanString($filters['whatsapp'] ?? '', 'whatsapp');
            if ($cleanWhatsapp !== '') {
                $conditions[] = "whatsapp LIKE :whatsapp";
                $params[':whatsapp'] = '%' . $cleanWhatsapp . '%';
            }
        }
        if (!empty($filters['instagram'])) {
            $conditions[] = "instagram LIKE :instagram";
            $params[':instagram'] = '%' . Database::cleanString($filters['instagram'] ?? '', 'instagram') . '%';
        }
        if (!empty($filters['website'])) {
            $cleanUrl = Database::cleanString($filters['website'] ?? '', 'url');
            if ($cleanUrl !== '') {
                $conditions[] = "website LIKE :website";
                $params[':website'] = '%' . $cleanUrl . '%';
            }
        }
        if (!empty($filters['observations'])) {
            $conditions[] = "_clean_observations LIKE :observations";
            $params[':observations'] = '%' . Database::cleanString($filters['observations'] ?? '') . '%';
        }

        if (!empty($conditions)) {
            $sql .= " AND (" . implode(" " . $operator . " ", $conditions) . ")";
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
}