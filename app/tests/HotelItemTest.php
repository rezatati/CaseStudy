<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

final class HotelItemTest extends TestCase
{
  /**
   * @var string $http
   */
  private $apiUrl = 'http://localhost:8085/api/v1/';

  /**
   * @var Client $http
   */
  private $http;

  public function setUp(): void
  {
    $this->http = new Client(['base_uri' => $this->apiUrl, 'options' => array(
      'exceptions' => false,
    )]);
  }

  public function tearDown(): void
  {
    $this->http = null;
  }
  public function testCanAddHotelItem(): int
  {
    $data = array(
      'name' => 'TestnameTestTest',
    );

    $response = $this->http->post('hotelier', ['form_params' => $data, 'http_errors' => false]);


    $result = json_decode($response->getBody()->getContents());
    $data = array(
      'name' => 'TestnameTestTest',
      'price' => '36',
      'zip' => '56869',
      'rating' => '2',
      'reputation' => '125',
      'category' => 'hotel',
      'availability' => '3',
      'image' => 'http://mnd.com/t.jpg',
      'hotelier_id' => $result->item->id,
      'state' => 'az',
      'country' => 'USA',
      'zip_code' => '36255',
      'address' => 'Boulevard Díaz Ordaz No. 9 Cantarranas',
      'city' => 'Losangles',
    );

    $response = $this->http->post('hotel-item', ['form_params' => $data, 'http_errors' => false]);


    $result = json_decode($response->getBody()->getContents());

    $this->assertEquals(200, $response->getStatusCode());
    return $result->item->id;
  }
  /**
   * @depends testCanAddHotelItem
   */
  public function testCanUpdateHotelItem($id): int
  {
    $data = [
      'name' => 'TestnameTestTest',
      'price' => '36',
      'zip' => '56869',
      'rating' => '2',
      'reputation' => '125',
      'category' => 'hotel',
      'availability' => '3',
      'image' => 'http://mnd.com/t.jpg',
      'hotel_item_id' => $id,
      'state' => 'az',
      'country' => 'USA',
      'zip_code' => '36255',
      'address' => 'Boulevard Díaz Ordaz No. 9 Cantarranas',
      'city' => 'Losangles',
    ];

    $response = $this->http->put('hotel-item', ['json' => $data, 'http_errors' => false]);
    $this->assertEquals(200, $response->getStatusCode());
    return $id;
  }
  /**
   * @depends testCanUpdateHotelItem
   */
  public function testCanGetHotelItem($id): int
  {
    $response = $this->http->get('hotel-item/' . $id, ['http_errors' => false]);
    $this->assertEquals(200, $response->getStatusCode());
    return $id;
  }
  /**
   * @depends testCanGetHotelItem
   */
  public function testCanDeleteHotelItem($id): int
  {
    $response = $this->http->delete('hotel-item/' . $id, ['http_errors' => false]);
    $this->assertEquals(200, $response->getStatusCode());
    return $id;
  }

  public function testCanNotAddHotelItemValidationError(): void
  {
    $data = array(
      'name' => 'test',
    );

    $response = $this->http->post('hotel-item', ['form_params' => $data, 'http_errors' => false]);
    $this->assertEquals(400, $response->getStatusCode());
  }
}