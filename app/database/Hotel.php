<?php

require "../bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('rooms', function ($table) {
  $table->increments('id');
  $table->string('name');
  $table->timestamps();
});