<?php


namespace AcquiaAPI\Response;


use AcquiaAPI\Client\Client;

class Application extends AcquiaResponse {

  /**
   * @var integer
   */
  protected $id;

  /**
   * @var string
   */
  protected $uuid;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var array
   */
  protected $hosting;

  /**
   * @var array
   */
  protected $subscription;

  /**
   * @var array
   */
  protected $organization;

  /**
   * @var array
   */
  protected $flags;

  /**
   * @var string
   */
  protected $status;

  /**
   * @var string
   */
  protected $type;

  /**
   * Application constructor.
   */
  public function __construct($response, Client $client) {
    parent::__construct($response, $client);
    $this->id = $this->decodedResponse['id'];
    $this->uuid = $this->decodedResponse['uuid'];
    $this->name = $this->decodedResponse['name'];
    $this->hosting = $this->decodedResponse['hosting'];
    $this->subscription = $this->decodedResponse['subscription'];
    $this->organization = $this->decodedResponse['organization'];
    $this->flags = $this->decodedResponse['flags'];
    $this->status = $this->decodedResponse['status'];
    $this->type = $this->decodedResponse['type'];
  }

  /**
   * @return string
   */
  public function getUuid() {
    return $this->uuid;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @return array
   */
  public function databases() {
    $uri = "applications/{$this->uuid}/databases";
    $response = $this->client->getRequest($uri);
    $acquia_response = new AcquiaResponse($response, $this->client);
    return $acquia_response->getEmbeddedItems();
  }

  /**
   * @return \AcquiaAPI\Response\MultipleResponse
   */
  public function environments() {
    $uri = "applications/{$this->uuid}/environments";
    $response = $this->client->getRequest($uri);
    return new MultipleResponse($response, $this->client, 'Environment');
  }

  public function __toString() {
    return "Application {$this->getName()} with UUID {$this->getUuid()}.\n";
  }

}