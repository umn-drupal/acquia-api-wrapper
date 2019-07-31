<?php


namespace Umndrupal\acquia_api\Response;

use Umndrupal\acquia_api\Client\Client;

class Backup extends AcquiaResponse
{

  /**
   * @var integer
   */
  protected $id;

  /**
   * @var array
   */
  protected $database;

  /**
   * @var array
   */
  protected $environment;

  /**
   * @var string
   */
  protected $type;

  /**
   * @var string
   */
  protected $started;

  /**
   * @var string
   */
  protected $completed;

  public function __construct($response, Client $client) {
    parent::__construct($response, $client);
    $this->id = $this->decodedResponse['id'];
    $this->database = $this->decodedResponse['database'];
    $this->environment = $this->decodedResponse['environment'];
    $this->type = $this->decodedResponse['type'];
    $this->started = $this->decodedResponse['started_at'];
    $this->completed = $this->decodedResponse['completed_at'];
  }

  public function download() {
    $uri = "environments/{$this->environment['id']}/databases/{$this->database['name']}/backups/{$this->id}/actions/download";
    $databaseFileResponse = $this->client->getRequest($uri);
  }

  public function delete() {
    $uri = "environments/{$this->environment['id']}/databases/{$this->database['name']}/backups/{$this->id}";
    $response = $this->client->deleteRequest($uri);
    return new AcquiaResponse($response, $this->client);
  }

  /**
   * @return int
   */
  public function getId(): int {
    return $this->id;
  }

  /**
   * @return bool
   */
  public function daily(): bool {
    return $this->type === 'daily';
  }

  /**
   * @return bool
   */
  public function onDemand() : bool {
    return $this->type === 'ondemand';
  }

  /**
   * @return string
   */
  public function getCompleted(): \DateTime {
    return new \DateTime($this->completed);
  }
}