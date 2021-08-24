<?php

require "../bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('locations')) {
  Capsule::schema()->create('locations', function ($table) {
    $table->increments('id');
    $table->unsignedBigInteger('hotel_item_id');
    $table->string('city');
    $table->string('state');
    $table->string('country');
    $table->string('zip_code');
    $table->string('address', 1000);
    $table->timestamps();
  });
}