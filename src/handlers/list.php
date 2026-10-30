<?php
// src/handlers/list.php
/** @var array $paths */
/** @var int $tab */

require_once $paths['files']['merchant'];

$filters = [];
$operator = 'OR';

// Se o usuário utilizou a busca genérica (?search=termo)
if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $term = trim($_GET['search']);
    
    // O handler distribui o termo para todas as colunas de busca (incluindo website)
    $filters = [
        'name'         => $term,
        'category'     => $term,
        'sub_category' => $term,
        'whatsapp'     => $term,
        'instagram'    => $term,
        'website'      => $term,
        'observations' => $term,
        'id'           => $term
    ];
} else {
    // Caso contrário, processa os filtros específicos individuais da URL
    if (isset($_GET['name']) && trim($_GET['name']) !== '') {
        $filters['name'] = trim($_GET['name']);
    }

    if (isset($_GET['category']) && trim($_GET['category']) !== '') {
        $filters['category'] = trim($_GET['category']);
    }

    if (isset($_GET['sub_category']) && trim($_GET['sub_category']) !== '') {
        $filters['sub_category'] = trim($_GET['sub_category']);
    }

    if (isset($_GET['whatsapp']) && trim($_GET['whatsapp']) !== '') {
        $filters['whatsapp'] = trim($_GET['whatsapp']);
    }

    if (isset($_GET['instagram']) && trim($_GET['instagram']) !== '') {
        $filters['instagram'] = trim($_GET['instagram']);
    }

    if (isset($_GET['website']) && trim($_GET['website']) !== '') {
        $filters['website'] = trim($_GET['website']);
    }

    if (isset($_GET['observations']) && trim($_GET['observations']) !== '') {
        $filters['observations'] = trim($_GET['observations']);
    }

    if (isset($_GET['id']) && trim($_GET['id']) !== '') {
        $filters['id'] = trim($_GET['id']);
    }
}

// Configurações de Paginação
$limit = 8;
$offset = ($tab - 1) * $limit;

// Executa a busca paginada e conta o total de registros correspondentes
$merchants = Merchant::search($filters, $operator, $limit, $offset);
$totalRecords = Merchant::countSearch($filters, $operator);
$totalTabs = ceil($totalRecords / $limit);

// SEGURANÇA: Impede que o $tab seja maior que o total real de páginas
if ($tab > $totalTabs) {
    $tab = $totalTabs;
    $offset = ($tab - 1) * $limit;
    // Refaz a busca com a aba corrigida para garantir os dados corretos
    $merchants = Merchant::search($filters, $operator, $limit, $offset);
}

// Categorias para o Table of Contents
$categories = Merchant::getCategories();

if ($totalTabs > 1) {
    if ($tab > 1) {
        $prevParams = $_GET;
        $prevParams['tab'] = $tab - 1;
    }

    if ($tab < $totalTabs) {
        $nextParams = $_GET;
        $nextParams['tab'] = $tab + 1;
    }

    $tabs = [];
    $maxVisible = 5;
    
    // Calcula a janela de exibição para mostrar no máximo 5 abas
    $start = max(1, min($tab - 2, $totalTabs - $maxVisible + 1));
    $end = min($totalTabs, $start + $maxVisible - 1);

    for ($i = $start; $i <= $end; $i++) {
        $pageParams = $_GET;
        $pageParams['tab'] = $i;

        $linkClass = 'tab-item';
        $linkClass .= ' ';
        $linkClass .= ($i === $tab) ? 'outline green' : 'outline secondary';
        
        $tabs[$i] = [
            'tab'       => $i,
            'url'       => 'index.php?' . http_build_query($pageParams),
            'linkClass' => $linkClass,
        ];
    }
}

// Define os estilos exclusivos desta página
$pageStyles = [
    'pico.min',
    'style',
    'search-form',
    'category-toc',
    'list-grid'
];