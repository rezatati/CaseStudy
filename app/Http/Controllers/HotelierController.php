<?php

namespace App\Http\Controllers;

class HotelierController
{

  public function save()
  {
    $this->validateInputData();
  }
  private function validateInputData()
  {
    $errors = [];
    $name = trim(strtolower($_POST['name']));
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
    $rating = (int)trim($_POST['rating']);
    if (!($rating >= 0 && $rating <= 5)) {
      $errors['rating'] = "rating must be valid number between 1-5";
    }
    $category = (int)trim(strtolower($_POST['category']));
    if (!isOneOfThem($category, ['hotel', 'alternative', 'hostel', 'lodge', 'resort', 'guest-house'])) {
      $errors['category'] = "category is not valid it must be one of the following: [hotel, alternative, hostel, lodge, resort, guest-house]";
    }
    $zip = trim($_POST['zip']);
    if (strlen($zip) != 5) {
      $errors['zip'] = "zipcode length  must be 5";
    }
    if (!is_numeric($zip)) {
      $errors['zip'] = "zipcode must be a number";
    }
    $image = trim($_POST['image']);
    if (filter_var($image, FILTER_VALIDATE_URL) === false) {
      $errors['image'] = "image must be a valid url";
    }
    $reputation = (int)trim($_POST['reputation']);
    if (!($reputation >= 0 && $reputation <= 1000)) {
      $errors['reputation'] = "reputation must be valid number between 0-1000";
    }
    $price = trim($_POST['price']);
    if (!is_numeric($price)) {
      $errors['price'] = "price must be a number";
    }
    $availability = trim($_POST['availability']);
    if (!is_numeric($availability)) {
      $errors['availability'] = "availability must be a number";
    }
    return $errors;
  }
}