<?php

/**
 *   Hotelier Model (ORM)
 * 
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotelier extends Model
{
  /**
   * return related holtels for each hotelier 
   * 
   */
  public function hotels()
  {
    return $this->hasMany(HotelItem::class);
  }
}