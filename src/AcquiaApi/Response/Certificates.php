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
    $this->id = $response['id'];
  }

  public function getEnvironment(): string
  {
    return $this->id;
  }

  public function getCSR() {
    $uri = "environments/{$this->id}/ssl/csrs";
    $options = [
      'headers' => [
        'Content-Type' => 'application/hal+json',
      ],
    ];
    $response = $this->client->getRequest($uri, $options);
    return $response->getBody()->getContents();
  }


  public function generateCSR($csr_data, $common_name, $csr_alternate_names=[]) {
    $uri = "environments/{$this->id}/ssl/csrs";
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

  public function deleteCSR($csrID) {
    $uri = "environments/{$this->id}/ssl/csrs/{$csrID}";
    $options = [
      'headers' => [
        'Content-Type' => 'application/hal+json',
      ],
    ];
    $response = $this->client->deleteRequest($uri, $options);
    return new AcquiaResponse($response, $this->client);
  }

  public function getCertificates() {
    $uri = "/environments/{$this->id}/ssl/certificates";
    $options = [
      'headers' => [
        'Content-Type' => 'application/hal+json',
      ],
    ];
    $response = $this->client->getRequest($uri, $options);
    return $response->getBody()->getContents();
  }

  public function installCertificate($label, $legacy, $certificate, $private_key,
    $ca_certificates, $flags, $expires_at, $domains, $csrID) {
    $uri = "/environments/{$this->id}/ssl/certificates";
    $cert_data["label"] = $label;
    $cert_data["legacy"] = $legacy;
    $cert_data["certificate"] = $certificate;
    $cert_data["private_key"] = $private_key;
    $cert_data["ca"] = $ca;
    $cert_data["flags"] = $flags;
    $cert_data["expires_at"] = $expires_at;
    $cert_data["domains"] = $domains;
    $cert_data["csrID"] = $csrID;
    $options = [
      'headers' => [
        'Content-Type' => 'application/hal+json',
      ],
      'form_params' => $cert_data,
    ];
    $response = $this->client->postRequest($uri, $options);
    return new AcquiaResponse($response, $this->client);
  }

  public function deleteCertificate($id, $certID) {
    $uri = "environment/{$id}/ssl/certificates/{$certID}";
    $options = [
      'headers' => [
        'Content-Type' => 'application/hal+json',
      ],
    ];
    $response = $this->client->deleteRequest($uri, $options);
    return new AcquiaResponse($response, $this->client);
  }

  public function activateCertificate($id, $certID) {
    $uri = "environment/{$id}/ssl/certificates/{$certID}/actions/activate";
    $options = [
      'headers' => [
        'Content-Type' => 'application/hal+json',
      ],
    ];
    $response = $this->client->postRequest($uri, $options);
    return new AcquiaResponse($response, $this->client);
  }

  public function deactivateCertificate($id, $certID) {
    $uri = "/environments/{$this->id}/ssl/certificates/actions/deactivate";
    $options = [
      'headers' => [
        'Content-Type' => 'application/hal+json',
      ],
    ];
    $response = $this->client->postRequest($uri, $options);
    return new AcquiaResponse($response, $this->client);
  }
}
