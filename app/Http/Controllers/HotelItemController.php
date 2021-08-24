<?php

namespace App\Http\Controllers;

use App\Models\Hotelier;
use App\Models\HotelItem;

class HotelItemController
{
  /**
   * book a Hotel item and decrease availability
   * 
   * @param int $itemID ID of Hotelitem to book
   */
  public function book($itemID)
  {
    $hotelItem = HotelItem::find((int)$itemID);
    if (!$hotelItem) {
      notFoundError(400, "invalid hotel item ID");
    }
    if ($hotelItem->availability > 0) {
      $hotelItem->availability--;
      $hotelItem->save();
      header('Content-type: application/json', true, 200);
      echo json_encode(['result' => true]);
      exit;
    } else {
      notFoundError(400, 'no more rooms on this hotel.');
    }
  }
  /**
   * delete a Hotel item  
   * 
   * @param int $itemID ID of Hotelitem to book
   */
  public function delete($itemID)
  {
    $hotelItem = HotelItem::find((int)$itemID);
    if (!$hotelItem) {
    }
    $hotelItem->delete();
    echo json_encode(['result' => true]);
    exit;
  }

  /**
   * retrive single Hotel item  
   * 
   * @param int $itemID ID of Hotelitem to book
   */
  public function get($itemID)
  {
    $hotelItem = HotelItem::with('location')->find((int)$itemID);
    if (!$hotelItem) {
      notFoundError(400, "invalid hotel item ID");
    }
    header('Content-type: application/json', true, 200);
    echo json_encode(['result' => true, 'item' => $hotelItem]);
    exit;
  }
  /**
   * create single Hotel item  
   * 
   *  
   */
  public function insert()
  {
    $result = $this->validateInputData();
    $hotelier = Hotelier::find((int)get_request_value('hotelier_id'));
    if (!$hotelier) {
      $result['errors']['hotelier_id'] = 'invalid hotelier ID!.';
    }

    if (count($result['errors'])) {
      returnValidatioErrors($result['errors']);
    }
    $hotelItem = new HotelItem();
    $hotelItem->hotelier_id = $hotelier->id;
    $hotelItem = $this->saveItem($hotelItem, $result);
    header('Content-type: application/json', true, 200);
    echo json_encode(['result' => true, 'item' => $hotelItem]);
    exit;
  }
  /**
   * update single Hotel item  
   * 
   */
  public function update()
  {
    $hotelItem = HotelItem::find((int)get_request_value('hotel_item_id'));
    if (!$hotelItem) {
      notFoundError(400, "invalid hotel item ID");
    }
    if (isset($_POST['hotelier_id']) && $hotelItem->hotelier_id != (int)$_POST['hotelier_id']) {
      $hotelier = Hotelier::find((int)get_request_value('hotelier_id'));
      if (!$hotelier) {
        $result['errors']['hotelier_id'] = 'invalid hotelier ID!.';
      } else {
        $hotelItem->hotelier_id = $hotelier->id;
      }
    }

    $result = $this->validateInputData();
    if (count($result['errors'])) {
      returnValidatioErrors($result['errors']);
    }
    $hotelItem = $this->saveItem($hotelItem, $result);
    header('Content-type: application/json', true, 200);
    echo json_encode(['result' => true, 'item' => $hotelItem]);
    exit;
  }

  /**
   * save or update hotel item  
   * 
   * @param HotelItem $hotelItem hotel item model that is going to be updated or saved
   * @param array $result result of validation 
   */
  private function saveItem($hotelItem, $result)
  {


    $hotelItem->name = $result['data']['name'];
    $hotelItem->rating = $result['data']['rating'];
    $hotelItem->category = $result['data']['category'];
    $hotelItem->image = $result['data']['image'];
    $hotelItem->zip = $result['data']['zip'];
    $hotelItem->image = $result['data']['image'];
    $hotelItem->reputation = $result['data']['reputation'];
    $hotelItem->price = $result['data']['price'];
    $hotelItem->availability = $result['data']['availability'];
    $hotelItem->save();
    if ($hotelItem->location) {
      $hotelItem->location->city = $result['data']['city'];
      $hotelItem->location->state = $result['data']['state'];
      $hotelItem->location->country = $result['data']['country'];
      $hotelItem->location->zip_code = $result['data']['zip_code'];
      $hotelItem->location->address = $result['data']['address'];
      $hotelItem->location->save();
    } else {

      $tmpData = [
        'city' => $result['data']['city'],
        'state' => $result['data']['state'],
        'country' => $result['data']['country'],
        'zip_code' => $result['data']['zip_code'],
        'address' => $result['data']['address'],
      ];
      $hotelItem->location()->create($tmpData);
    }
    return $hotelItem;
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
      if (contains($name, ["free", "offer", "book", "website"])) {
        $errors['name'] = 'name cannot contains "Free", "Offer", "Book", "Website"';
      }
    }
    $rating = trim(get_request_value('rating'));
    $values['rating'] = $rating;
    if (!$rating) {
      $errors['rating'] = "rating is required";
    }
    if (!((int)$rating >= 0 && (int)$rating <= 5)) {
      $errors['rating'] = "rating must be valid number between 1-5";
    }
    $category = trim(strtolower(get_request_value('category')));
    $values['category'] = $category;
    if (!isOneOfThem($category, ['hotel', 'alternative', 'hostel', 'lodge', 'resort', 'guest-house'])) {
      $errors['category'] = "category is not valid it must be one of the following: [hotel, alternative, hostel, lodge, resort, guest-house]";
    }
    $zip = trim(get_request_value('zip'));
    $values['zip'] = $zip;
    if (strlen($zip) != 5) {
      $errors['zip'] = "zipcode length  must be 5";
    }
    if (!is_numeric($zip)) {
      $errors['zip'] = "zipcode must be a number";
    }
    $image = trim(get_request_value('image'));
    $values['image'] = $image;
    if (filter_var($image, FILTER_VALIDATE_URL) === false) {
      $errors['image'] = "image must be a valid url";
    }
    $reputation = trim(get_request_value('reputation'));
    $values['reputation'] = (int)$reputation;
    if (!$reputation) {
      $errors['reputation'] = "reputation is required";
    }
    if (!((int)$reputation >= 0 && (int)$reputation <= 1000)) {
      $errors['reputation'] = "reputation must be valid number between 0-1000";
    }
    $price = trim(get_request_value('price'));
    $values['price'] = $price;
    if (!is_numeric($price)) {
      $errors['price'] = "price must be a number";
    }
    $availability = trim(get_request_value('availability'));
    $values['availability'] = $availability;
    if (!is_numeric($availability)) {
      $errors['availability'] = "availability must be a number";
    }
    $city = trim(get_request_value('city'));
    $values['city'] = $city;
    if (!$city) {
      $errors['city'] = "city is required.";
    }
    $state = trim(get_request_value('state'));
    $values['state'] = $state;
    if (!$state) {
      $errors['state'] = "state is required.";
    }
    $country = trim(get_request_value('country'));
    $values['country'] = $country;
    if (!$country) {
      $errors['country'] = "country is required.";
    }

    $zip_code = trim(get_request_value('zip_code'));
    $values['zip_code'] = $zip_code;
    if (strlen($zip_code) != 5) {
      $errors['zip_code'] = "zipcode length  must be 5";
    }
    if (!is_numeric($zip_code)) {
      $errors['zip_code'] = "zipcode must be a number";
    }

    $address = trim(get_request_value('address'));
    $values['address'] = $address;
    if (!$address) {
      $errors['address'] = "address is required.";
    }
    return ['errors' => $errors, 'data' => $values];
  }
}