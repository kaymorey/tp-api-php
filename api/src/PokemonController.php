<?php

require_once 'src/PokemonQuery.php';

class PokemonController
{
  public function __construct($query)
  {
    $this->query = $query;
  }
  
  public function processRequest($method, $id = null)
  {
    if ($id) {
      $this->processSingleElementRequest($method, $id);
    } else {
      $this->processCollectionRequest($method);
    }
  }

  private function processSingleElementRequest($method, $id) {
    $pokemon = $this->query->get($id);
    if (!$pokemon) {
      http_response_code(404);
      echo json_encode([
        'message' => 'Pokemon not found'
      ]);
      return;
    }

    switch ($method) {
      case 'GET':
        echo json_encode($pokemon);
        break;
      case 'DELETE':
        $rows = $this->query->remove($id);
        echo json_encode([
          'message' => 'Pokemon was successfully deleted',
          'nb_rows' => $rows
        ]);
        break;
      case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $errors = $this->getValidationErrors($data, false);
        if (!empty($errors)) {
          http_response_code(422);
          echo json_encode([
            'message' => 'Validation errors',
            'errors' => $errors
          ]);
          break;
        }

        $this->query->update($pokemon, $data);
        echo json_encode([
          'message' => 'Pokemon with id ' . $id . ' was successfully updated'
        ]);
        break;
      default:
        http_response_code(405);
        header('Allow: GET, DELETE, PUT');
        echo json_encode([
          'message' => 'Method not allowed'
        ]);
    }
  }

  private function processCollectionRequest($method) {
   switch ($method) {
      case 'GET':
        $pokemons = $this->query->getAll();
        echo json_encode($pokemons);
        break;
      case 'POST';
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data) && !empty($_POST)) {
          $data = $_POST;
        }
        $errors = $this->getValidationErrors($data);
        if (!empty($errors)) {
          http_response_code(422);
          echo json_encode([
            'message' => 'Validation errors',
            'errors' => $errors
          ]);
          break;
        }

        $id = $this->query->create($data);
        http_response_code(201);
        echo json_encode([
          'message' => 'Pokemon created',
          'id' => $id
        ]);
        break;
      default:
        http_response_code(405);
        header('Allow: GET, POST');
        echo json_encode([
          'message' => 'Method not allowed'
        ]);
    }
  }

  private function getValidationErrors($data, $new_object = true) {
    $errors = [];
    // Vérifier aussi le bon format des données : par exemple ici que hp est bien un int
    if ($new_object) {
      if (!isset($data['name']) || empty($data['name'])) {
        $errors['name'] = 'Name is required';
      }
      if (!isset($data['type']) || empty($data['type'])) {
        $errors['type'] = 'Type is required';
      }
      if (!isset($data['hp']) || empty($data['hp'])) {
        $errors['hp'] = 'HP is required';
      }
    }
    return $errors;
  }
}