<?php

require_once 'src/Database.php';
require_once 'src/PokemonController.php';

header('Content-Type: application/json; charset=UTF-8');

$parts = explode('/', $_SERVER['REQUEST_URI']);

if ($parts[3] != 'pokemons') {
  http_response_code(404);
  exit;
}

$id = $parts[4] ?? null;

$database = new Database('localhost', 'pokedex', 'root', 'root', 8889);
try {
  $pdo = $database->getConnection();
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode([
    'message' => 'Database connection error'
  ]);
}

$query = new PokemonQuery($pdo);
$controller = new PokemonController($query);
$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);