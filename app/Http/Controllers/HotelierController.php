<?php

namespace App\Http\Controllers;

use App\Models\Hotelier;

class HotelierController
{

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
}