<?php
// public/index.php

// Carrega os caminhos globais e o roteador
$paths = require_once __DIR__ . '/../src/paths.php';
require_once $paths['files']['utils'];
require_once $paths['files']['auth'];
require_once $paths['files']['router'];