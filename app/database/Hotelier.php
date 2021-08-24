<?php

require "../bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('hoteliers')) {
  Capsule::schema()->create('hoteliers', function ($table) {
    $table->increments('id');
    $table->string('name');
    $table->timestamps();
  });
}