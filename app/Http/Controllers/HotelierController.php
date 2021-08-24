<?php

namespace App\Http\Controllers;

use App\Models\Hotelier;

class HotelierController
{

  /**
   * get Hotelier with related hotel items and locations 
   * 
   * @param int $hotelierID ID of Hotelier that to be retrived from DB
   */
  public function index($hotelierID)
  {

    $hotelier = Hotelier::with(['hotels', 'hotels.location'])->find((int)$hotelierID);
    if (!$hotelier) {
      notFoundError(400, "invalidhotelier ID");
    }
    header('Content-type: application/json', true, 200);
    echo json_encode(['result' => true, 'items' => $hotelier->hotels]);
    exit;
  }

  /**
   * create single Hotelier  
   * 
   */
  public function insert()
  {
    $result = $this->validateInputData();
    $hotelier = new  Hotelier();
    if (count($result['errors'])) {
      returnValidatioErrors($result['errors']);
    }
    $hotelier->name = $result['data']['name'];
    $hotelier->save();
    header('Content-type: application/json', true, 200);
    echo json_encode(['result' => true, 'item' => $hotelier]);
    exit;
  }
  /**
   * validate request data and return pure data for saving or updating
   * 
   */
  private function validateInputData()
  {
    $values = [];
    $errors = [];
    $name = trim(strtolower(get_request_value('name')));
    $values['name'] = $name;
    if (!$name) {
      $errors['name'] = 'name is required.';
    } else {
      if (strlen($name) < 10) {
        $errors['name'] = 'name must be greater than 10 characters.';
      }
    }
    return ['errors' => $errors, 'data' => $values];
  }
}