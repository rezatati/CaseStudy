<?php

/**
 * list of defined routes 
 * 
 */
return [
  [
    "url" => "/hotelier",
    "method" => "post",
    "handler" => "App\Http\Controllers\HotelierController::insert",
  ],
  [
    "url" => "/hotelier",
    "method" => "get",
    "pattern" => '/\d+/',
    "handler" => "App\Http\Controllers\HotelierController::index",
  ],
  [
    "url" => "/hotel-item",
    "method" => "post",
    "handler" => "App\Http\Controllers\HotelItemController::insert",
  ],
  [
    "url" => "/hotel-item",
    "method" => "put",
    "handler" => "App\Http\Controllers\HotelItemController::update",
  ],
  [
    "url" => "/hotel-item",
    "method" => "get",
    "pattern" => '/\d+/',
    "handler" => "App\Http\Controllers\HotelItemController::get",
  ],
  [
    "url" => "/hotel-item",
    "method" => "delete",
    "pattern" => '/\d+/',
    "handler" => "App\Http\Controllers\HotelItemController::delete",
  ],
  [
    "url" => "/hotel-item/book",
    "method" => "get",
    "pattern" => '/\d+/',
    "handler" => "App\Http\Controllers\HotelItemController::book",
  ],
];