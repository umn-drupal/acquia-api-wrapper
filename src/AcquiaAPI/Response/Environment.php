<?php

namespace Umndrupal\acquia_api\Response;

use Umndrupal\acquia_api\Client\Client;

class Environment extends AcquiaResponse
{

  /**
   * @var string
   */
  protected $id;

  /**
   * @var string
   */
  protected $label;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var array
   */
  protected $application;

  /**
   * @var array
   */
  protected $domains;

  /**
   * @var string
   */
  protected $activeDomain;

  /**
   * @var string
   */
  protected $defaultDomain;

  /**
   * @var string
   */
  protected $imageUrl;

  /**
   * @var string
   */
  protected $sshUrl;

  /**
   * @var string
   */
  protected $region;

  /**
   * @var string
   */
  protected $status;

  /**
   * @var string
   */
  protected $type;

  /**
   * @var string
   */
  protected $size;

  /**
   * @var integer
   */
  protected $weight;

  /**
   * @var array
   */
  protected $vcs;

  /**
   * @var array
   */
  protected $insight;

  /**
   * @var array
   */
  protected $flags;

  /**
   * @var array
   */
  protected $configuration;

  /**
   * @var array
   */
  protected $artifact;

  /**
   * Environment constructor.
   *
   * @inheritDoc
   */
  public function __construct($response, Client $client)
  {
    parent::__construct($response, $client);
    $this->id = $this->decodedResponse['id'];
    $this->label = $this->decodedResponse['label'];
    $this->name = $this->decodedResponse['name'];
    $this->application = $this->decodedResponse['application'];
    $this->domains = $this->decodedResponse['domains'];
    $this->activeDomain = $this->decodedResponse['active_domain'];
    $this->defaultDomain = $this->decodedResponse['default_domain'];
    $this->imageUrl = $this->decodedResponse['image_url'];
    $this->sshUrl = $this->decodedResponse['ssh_url'];
    $this->region = $this->decodedResponse['region'];
    $this->status = $this->decodedResponse['status'];
    $this->type = $this->decodedResponse['type'];
    $this->size = $this->decodedResponse['size'];
    $this->weight = $this->decodedResponse['weight'];
    $this->vcs = $this->decodedResponse['vcs'];
    //  $this->insight = $this->decodedResponse['insight'];
    $this->flags = $this->decodedResponse['flags'];
    $this->configuration = $this->decodedResponse['configuration'];
    $this->artifact = $this->decodedResponse['artifact'];
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
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * @return string
   */
  public function getApplication(): string
  {
    return $this->application;
  }

  public function getCrons()
  {
    $uri = "environments/{$this->id}/crons";
    $response = $this->client->getRequest($uri);
    return new MultipleResponse($response, $this->client, 'Cron');
  }

  public function databases()
  {
    $uri = "environments/{$this->getId()}/databases";
    $response = $this->client->getRequest($uri);
    return new MultipleResponse($response, $this->client, 'Database');
  }

  public function copyDatabaseToEnv($databaseName, Environment $sourceEnvironment) {
    $uri = "environments/{$this->getId()}/databases";
    $options = [
      'json' => [
        'name' => $databaseName
        'source' => $sourceEnvironment->getId(),
      ],
    ];
    $response = $this->client->postRequest($uri, $options);
    return new AcquiaResponse($response, $this->client);
  }

  public function getDomains()
  {
    if (count($this->domains) === 0) {
      $this->setDomains();
    }
    return $this->domains;
  }

  protected function setDomains()
  {
    $uri = "environments/{$this->id}/domains";
    $response = $this->client->getRequest($uri);
    $acquia_response = new AcquiaResponse($response, $this->client);
    $domains = array_map(function ($i) {
      return $i['hostname'];
    }, $acquia_response->getEmbeddedItems());
    $this->domains = $domains;
  }

  /**
   * @param string $hostname
   *
   * @return \AcquiaAPI\Response\AcquiaResponse
   */
  public function addDomain(string $hostname)
  {
    $uri = "environments/{$this->id}/domains";
    $options = [
      'json' => [
        'hostname' => $hostname
      ],
    ];
    $response = $this->client->postRequest($uri, $options);
    return new AcquiaResponse($response, $this->client);
  }

  /**
   * @param string $hostname
   *
   * @return \AcquiaAPI\Response\AcquiaResponse
   */
  public function removeDomain(string $hostname)
  {
    $uri = "environments/{$this->id}/domains/{$hostname}";
    $response = $this->client->deleteRequest($uri);
    return new AcquiaResponse($response, $this->client);
  }

  /**
   * @param string[] $domains
   *
   * @return \AcquiaAPI\Response\AcquiaResponse
   */
  public function clearVarnishOnDomains(array $domains)
  {
    $uri = "environments/{$this->id}/domains/actions/clear-varnish";
    $options = [
      'json' => [
        'domains' => $domains,
      ],
    ];
    $response = $this->client->postRequest($uri, $options);
    return new AcquiaResponse($response, $this->client);
  }

  public function __toString()
  {
    $output = "Environment {$this->getName()} with ID {$this->getId()} and label {$this->label}.\n";
    return $output;
  }
}
