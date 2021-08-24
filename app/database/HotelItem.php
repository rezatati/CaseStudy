<?php

require "../bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('hotel_items')) {
  Capsule::schema()->create('hotel_items', function ($table) {
    $table->increments('id');
    $table->string('name');
    $table->smallInteger('rating');
    $table->unsignedBigInteger('hotelier_id');
    $table->smallInteger('reputation');
    $table->string('category');
    $table->text('image');
    $table->integer('zip');
    $table->integer('availability');
    $table->decimal('price', 10, 2);
    $table->timestamps();
  });
}