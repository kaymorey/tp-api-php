<?php

class TypesQuery
{
  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function getAll()
  {
    $sql = 'SELECT * FROM types';
    $statement = $this->pdo->prepare($sql);
    $statement->execute();
    
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }
}