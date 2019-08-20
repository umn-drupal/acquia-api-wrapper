<?php

namespace Umndrupal\acquia_api\Response;

use Umndrupal\acquia_api\Client\Client;

class Application extends AcquiaResponse
{

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
  public function __construct($response, Client $client)
  {
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
  public function getUuid()
  {
    return $this->uuid;
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @return array
   */
  public function databases()
  {
    $uri = "applications/{$this->uuid}/databases";
    $response = $this->client->getRequest($uri);
    $acquia_response = new AcquiaResponse($response, $this->client);
    return $acquia_response->getEmbeddedItems();
  }

  /**
   * @param string $databaseName
   * @return \Umndrupal\acquia_api\Response\AcquiaResponse
   */
  public function createDatabase(string $databaseName)
  {
    $uri = "applications/{$this->uuid}/databases";
    $options = [
      'json' => [
        'name' => $databaseName,
      ],
    ];
    $response = $this->client->postRequest($uri, $options);
    return new AcquiaResponse($response, $this->client);
  }

  /**
   * @return \Umndrupal\acquia_api\Response\MultipleResponse
   */
  public function environments()
  {
    $uri = "applications/{$this->uuid}/environments";
    $response = $this->client->getRequest($uri);
    return new MultipleResponse($response, $this->client, 'Environment');
  }

  /**
   * @param int $total
   *
   * @return array
   */
  public function notifications($total = 50)
  {
    $uri = "applications/{$this->uuid}/notifications";
    // Currently API doesn't allow sort by date, so we can't use the limit
    // param. Until then, reverse the array and slice it, @todo replace with
    // sort and limit when available.
    $response = $this->client->getRequest($uri);//, ['query' => ['limit' => $total]]);
    $acquia_response = new AcquiaResponse($response, $this->client);
    $notifications = array_slice(
      array_reverse($acquia_response->getEmbeddedItems()),
      0,
      $total
    );
    return $notifications;
  }

  public function __toString()
  {
    return "Application {$this->getName()} with UUID {$this->getUuid()}.\n";
  }
}
