<?php

namespace App\Http\Controllers;

use App\Models\Hotelier;
use App\Models\HotelItem;

class HotelItemController
{
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
  public function delete($itemID)
  {
    $hotelItem = HotelItem::find((int)$itemID);
    if (!$hotelItem) {
    }
    $hotelItem->delete();
    echo json_encode(['result' => true]);
    exit;
  }
  public function get($itemID)
  {
    $hotelItem = HotelItem::find((int)$itemID);
    if (!$hotelItem) {
      notFoundError(400, "invalid hotel item ID");
    }
    header('Content-type: application/json', true, 200);
    echo json_encode(['result' => true, 'item' => $hotelItem]);
    exit;
  }
  public function insert()
  {
    $result = $this->validateInputData();
    $hotelier = Hotelier::find((int)get_request_value('hotelier_id'));
    if (!$hotelier) {
      $result['errors']['hotelier_id'] = 'invalid hotelier ID!.';
    }

    if (count($result['errors'])) {
      $this->returnValidatioErrors($result['errors']);
    }
    $hotelItem = new HotelItem();
    $hotelItem->hetelier_id = $hotelier->id;
    $this->saveItem($hotelItem, $result);
  }
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
        $hotelItem->hetelier_id = $hotelier->id;
      }
    }

    $result = $this->validateInputData();
    if (count($result['errors'])) {
      $this->returnValidatioErrors($result['errors']);
    }
    $this->saveItem($hotelItem, $result);
  }
  private function returnValidatioErrors($errors)
  {
    $errs = [];
    foreach ($errors as $key => $value) {
      $errs[] = [
        "name" => $key,
        "reason" => $value
      ];
    }
    header('Content-type:application/problem+json', true, 400);
    echo json_encode([
      "type" => "https://example.net/validation-error",
      "title" => "Your request parameters didn't validate.",
      'invalid-params' => $errs
    ]);
    exit;
  }
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
  }
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
    return ['errors' => $errors, 'data' => $values];
  }
}