<?php

require "../bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('hotel_item')) {
  Capsule::schema()->create('hotel_item', function ($table) {
    $table->increments('id');
    $table->string('name');
    $table->timestamps();
  });
}