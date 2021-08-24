<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotelier extends Model
{
  public function hotels()
  {
    return $this->hasMany(HotelItem::class);
  }
}