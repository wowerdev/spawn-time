<?php
require __DIR__ . '/function.php';

// Вывод результатов в формате JSON
header('Content-Type: application/json');
echo getJsonSpawnData();
