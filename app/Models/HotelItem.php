<?php

/**
 *   Hotel Item Model (ORM)
 * 
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelItem extends Model
{
  /**
   * @var array $appends appendable attribite to this model
   * 
   */
  protected $appends = ['reputationBadge'];
  /**
   *  generate Reputation Badg based on Reputation
   * 
   */
  public function getReputationBadgeAttribute()
  {

    if ($this->reputation > 799) {
      return 'green';
    } elseif ($this->reputation > 500) {
      return 'yellow';
    }
    return 'red';
  }

  /**
   * return related location for each hotel item 
   * 
   */
  public function location()
  {
    return $this->hasOne(Location::class);
  }
}