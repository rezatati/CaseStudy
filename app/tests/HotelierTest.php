<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

final class HotelierTest extends TestCase
{
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

  public function testCanAddHotel(): void
  {
    $data = array(
      'name' => 'TestnameTestTest',
    );

    $response = $this->http->post('hotelier', ['form_params' => $data, 'http_errors' => false]);


    $result = json_decode($response->getBody()->getContents());

    $this->assertEquals(200, $response->getStatusCode());
  }
}