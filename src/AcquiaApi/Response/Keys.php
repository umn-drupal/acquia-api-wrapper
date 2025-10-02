<?php

namespace Umndrupal\acquia_api\Response;

use Umndrupal\acquia_api\Client\Client;

class Keys extends AcquiaResponse {

  /**
   * @var \Umndrupal\acquia_api\Client\Client
   */
  protected $client;

  /**
   * @var string|mixed
   */
  protected string $public_key;

  /**
   * @var string|mixed
   */
  protected string $uuid;

  /**
   * @var string|mixed
   */
  protected string $label;


  /**
   * Tokens constructor.
   */
  public function __construct($response, Client $client) {
    parent::__construct($response, $client);
    $this->client = $client;
    $this->uuid = isset($response['uuid']) ? $response['uuid'] : '';
    $this->label = isset($response['label']) ? $response['label'] : '';
    $this->public_key = isset($response['public_key']) ? $response['public_key'] : '';
//    $this->response = $response;
  }

  public function getKeys() {
    $uri = "account/ssh-keys";
    $response = $this->client->getRequest($uri);
    return new MultipleResponse($response, $this->client, 'Keys');
  }

//  public function deleteTokens() {
//    $uri = "account/tokens/{$this->uuid}";
//    $response = $this->client->deleteRequest($uri);
//    return new AcquiaResponse($response, $this->client);
//  }
//
  public function addKeys() {
    $uri = "account/ssh-keys";
    $key_info['label'] = $this->label;
    $key_info['public_key'] = $this->public_key;
    $key_json = json_encode($key_info);
    $options = [
      'headers' => [
        'Content-Type' => 'application/json',
      ],
      'body' => $key_json,
    ];
    $response = $this->client->postRequest($uri, $options);
    return new AcquiaResponse($response, $this->client);
  }
}
