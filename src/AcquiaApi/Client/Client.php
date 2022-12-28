<?php

namespace Umndrupal\acquia_api\Client;

use Umndrupal\acquia_api\Response\Application;
use Umndrupal\acquia_api\Response\Environment;
use Umndrupal\acquia_api\Response\MultipleResponse;
use Umndrupal\acquia_api\Response\Logs;
use GuzzleHttp\Client as GClient;
use Umndrupal\acquia_api\Oauth\Provider;
use Psr\Http\Message\RequestInterface;

class Client extends GClient
{

  /**
   * @var Provider
   */
  private $provider;

  public function __construct($key, $secret, array $config = [])
  {
    $config['base_uri'] = Provider::$api_base;
    $this->provider = new Provider($key, $secret);
    parent::__construct($config);
  }

  public function applications($applicationId = '')
  {
    $uri = 'applications/' . $applicationId;
      $response = $this->getRequest($uri);
    if (!empty($applicationId)) {
      return new Application($response, $this);
    } else {
      return new MultipleResponse($response, $this, 'Application');
    }
  }

  /**
   * @param string $environmentId
   */
  public function environment($environmentId)
  {
    $uri = 'environments/' . $environmentId;
    $response = $this->getRequest($uri);
    return new Environment($response, $this);
  }

  public function logs($log_types) {
    $this->id = $log_types['id'];
    $this->log_type = $log_types['log_type'];
    $uri = 'environments/' . $log_types['id'] . '/logs';
    $response = $this->getRequest($uri);
    return new Logs($response, $this);
  }

  public function getRequest($uri, $options = [])
  {
    $request = $this->provider->request('GET', $uri);
    return $this->sendRequest($request, $options);
  }

  public function postRequest($uri, array $options = [])
  {
    $request = $this->provider->request('POST', $uri, $options);
    //    $test = $this->request('POST', $uri, $options);
    return $this->sendRequest($request, $options);
  }

  public function deleteRequest($uri)
  {
    $request = $this->provider->request('DELETE', $uri);
    return $this->sendRequest($request);
  }

  /**
   * @param \Psr\Http\Message\RequestInterface $request
   *
   * @return mixed|\Psr\Http\Message\ResponseInterface
   */
  public function sendRequest(RequestInterface $request, array $options = [])
  {
    return $this->send($request, $options);
  }

}
