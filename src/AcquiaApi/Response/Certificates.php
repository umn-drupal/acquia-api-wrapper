<?php

namespace Umndrupal\acquia_api\Response;

use Umndrupal\acquia_api\Client\Client;

class Certificates extends AcquiaResponse {

  /**
   * @var string
   */
  protected $id;

  /**
   * @var array
   */
  protected $csr_data;

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

  public function getCSRData(): array
  {
    return [
      "country" => "US",
      "state" => "Minnesota",
      "locality" => "Minneapolis",
      "organization" => "University of Minnesota",
      "organizational_unit" => "OIT",
    ];
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


  public function generateCSR($id, $common_name, $csr_alternate_names=[]) {
    $uri = "environments/{$this->id}/ssl/csrs";
    $csr_data = $this->getCSRData();
    $csr_data["common_name"] = $common_name;
    $csr_data["alternate_names"] = $csr_alternate_names;
    $options = [
      'headers' => [
        'Content-Type' => 'application/hal+json',
      ],
      'form_params' => $csr_data,
    ];
    $response = $this->client->postRequest($uri, $options);
    return new AcquiaResponse($response, $this->client);
  }
}
