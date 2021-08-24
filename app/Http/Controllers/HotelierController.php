<?php

namespace App\Http\Controllers;

use App\Models\Hotelier;

class HotelierController
{

  public function index($hotelierID)
  {

    $hotelier = Hotelier::find((int)$hotelierID);
    if (!$hotelier) {
      header('Content-type: application/json', true, 404);
      echo json_encode(['result' => false, 'errors' => ['hotelier' => "invalid hotelier ID"]]);
      exit;
    }
    header('Content-type: application/json', true, 200);
    echo json_encode(['result' => true, 'items' => $hotelier->hotels]);
    exit;
  }
}