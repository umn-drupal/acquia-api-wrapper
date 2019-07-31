<?php

namespace Umndrupal\acquia_api\Response;

use Umndrupal\acquia_api\Client\Client;

class Cron extends AcquiaResponse
{

  /**
   * @var string
   */
  protected $id;

  /**
   * @var array
   */
  protected $server;

  /**
   * @var string
   */
  protected $command;

  /**
   * @var string
   */
  protected $minute;

  /**
   * @var string
   */
  protected $hour;

  /**
   * @var string
   */
  protected $day_month;

  /**
   * @var string
   */
  protected $month;

  /**
   * @var string
   */
  protected $day_week;

  /**
   * @var string
   */
  protected $label;

  /**
   * @var array
   */
  protected $flags;
  /**
   * @var array
   */
  protected $environment;

  /**
   * @inheritDoc
   */
  public function __construct($response, Client $client)
  {
    parent::__construct($response, $client);
    $this->id = $this->decodedResponse['id'];
    $this->server = $this->decodedResponse['server'];
    $this->command = $this->decodedResponse['command'];
    $this->minute = $this->decodedResponse['minute'];
    $this->hour = $this->decodedResponse['hour'];
    $this->day_month = $this->decodedResponse['day_month'];
    $this->month = $this->decodedResponse['month'];
    $this->day_week = $this->decodedResponse['day_week'];
    $this->label = $this->decodedResponse['label'];
    $this->flags = $this->decodedResponse['flags'];
    $this->environment = $this->decodedResponse['environment'];
  }

  /**
   * @return string
   */
  public function getId(): string
  {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getCommand(): string
  {
    return $this->command;
  }

  public function enabled(): bool
  {
    return $this->flags['enabled'];
  }

  public function getFrequency(): string
  {
    return "{$this->minute} {$this->hour} {$this->day_month} {$this->month} {$this->day_week}";
  }

  public function enableCron(): AcquiaResponse
  {
    $uri = "environments/{$this->environment['id']}/crons/{$this->id}/actions/enable";
    $request = $this->client->postRequest($uri);
    return new AcquiaResponse($request, $this->client);
  }

  public function disableCron(): AcquiaResponse
  {
    $uri = "environments/{$this->environment['id']}/crons/{$this->id}/actions/disable";
    $request = $this->client->postRequest($uri);
    return new AcquiaResponse($request, $this->client);
  }

  public function __toString()
  {
    return "Cron command: {$this->command}\nFrequency: {$this->getFrequency()}";
  }
}
