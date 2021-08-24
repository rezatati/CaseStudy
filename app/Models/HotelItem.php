<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelItem extends Model
{
  protected $appends = ['reputationBadge'];

  public function getReputationBadgeAttribute()
  {

    if ($this->reputation > 799) {
      return 'green';
    } elseif ($this->reputation > 500) {
      return 'yellow';
    }
    return 'red';
  }


  public function location()
  {
    return $this->hasOne(Location::class);
  }
}