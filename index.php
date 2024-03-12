<?php
  require_once 'api/src/Database.php';
  require_once 'api/src/TypesQuery.php';

  $database = new Database('localhost', 'pokedex', 'root', 'root', 8889);
  $pdo = $database->getConnection();
  $query = new TypesQuery($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Test formulaire</title>
  <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<body>
  <div>
    <h1>Ajout d'un pokémon</h1>
    <form action="http://localhost:8888/php-api/api/pokemons" method="POST">
      <p>
        <label for="name">Nom</label>
        <input type="text" name="name" id="name">
      </p>
      <p>
        <label for="hp">HP</label>
        <input type="text" name="hp" id="hp">
      </p>
      <p>
        <?php 
          $types = $query->getAll();
        ?>
        <label for="type">Type</label>
        <select name="type" id="type">
          <?php foreach ($types as $type) : ?>
            <option value="<?= $type['id']; ?>"><?= $type['name'] ?></option>
          <?php endforeach; ?>
        </select>
      </p>
      <p>
        <input type="submit" value="Valider">
      </p>
    </form>
  </div>
</body>
</html>