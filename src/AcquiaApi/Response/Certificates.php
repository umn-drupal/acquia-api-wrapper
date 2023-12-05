<?php

namespace Umndrupal\acquia_api\Response;

use Umndrupal\acquia_api\Client\Client;

class Certificates extends AcquiaResponse {

  /**
   * @var string
   */
  protected $id;

  public function __construct($response, Client $client)
  {
    parent::__construct($response, $client);
    $properties = [
      'id',
    ];
  }

  public function getEnvironment(): string
  {
    return $this->id;
  }

  public function getCSR($id) {
    $uri = "environments/{$this->id}/ssl/csrs";
    $options = [
      'headers' => [
        'Content-Type' => 'application/hal+json',
      ],
    ];
    $response = $this->client->getRequest($uri, $options);
    return $response->getBody()->getContents();
  }
}
