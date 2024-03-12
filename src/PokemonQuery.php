<?php

class PokemonQuery
{
  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function getAll($id = null)
  {
    $sql = 'SELECT * FROM pokemons';
    if ($id) {
      $query .= ' WHERE id = :id';
    }
    $statement = $this->pdo->prepare($sql);
    if ($id) {
      $statement->bindParam(':id', $id);
    }
    $statement->execute();
    
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public function create($data)
  {
    $sql = 'INSERT INTO pokemons (name, type, hp) VALUES (:name, :type, :hp)';
    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':name', $data['name'], PDO::PARAM_STR);
    $statement->bindValue(':type', $data['type'], PDO::PARAM_INT);
    $statement->bindValue(':hp', $data['hp'], PDO::PARAM_INT);
    $statement->execute();

    return $this->pdo->lastInsertId();
  }

  public function get($id)
  {
    $sql = 'SELECT * FROM pokemons WHERE id = :id';
    $statement = $this->pdo->prepare($sql);
    $statement->bindParam(':id', $id);
    $statement->execute();

    $data = $statement->fetch(PDO::FETCH_ASSOC);

    return $data;
  }

  public function remove($id)
  {
    $sql = 'DELETE FROM pokemons WHERE id = :id';
    $statement = $this->pdo->prepare($sql);
    $statement->bindParam(':id', $id);
    $statement->execute();

    // Retourne le nombre de lignes effacées
    return $statement->rowCount();
  }

  public function update($pokemon, $data)
  {
    $sql = 'UPDATE pokemons set name = :name, type = :type, hp = :hp WHERE id = :id';
    $statement = $this->pdo->prepare($sql);
    $name = $data['name'] ? $data['name'] : $pokemon['name'];
    $hp = $data['hp'] ? $data['hp'] : $pokemon['hp'];
    $type = $data['type'] ? $data['type'] : $pokemon['type'];
    $statement->bindParam(':name', $name);
    $statement->bindParam(':hp', $hp);
    $statement->bindParam(':type', $type);
    $statement->bindParam(':id', $pokemon['id']);

    $statement->execute();
  }
}