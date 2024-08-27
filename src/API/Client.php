<?php

namespace Vinkas\Discourse\PHP\API;

use Vinkas\Discourse\PHP\Client as Discourse;

class Client
{

  protected $http;
  protected $discourse;

  public function __construct(Discourse $discourse, $api_key, $api_username = 'system') {
    $this->discourse = $discourse;
    $this->http = new \GuzzleHttp\Client(['base_uri' => $this->discourse->getUrl()]);
    $this->setDefaultQueryParams($api_key, $api_username);
  }

  protected $queryParams;

  protected function getDefaultQueryParams() {
    return $this->queryParams;
  }

  protected function setDefaultQueryParams($api_key, $api_username) {
    $this->queryParams = array();
    $this->queryParams['api_key'] = $api_key;
    $this->queryParams['api_username'] = $api_username;
  }

  public function setUsername($api_username) {
    $this->queryParams['api_username'] = $api_username;
  }

  public function request($method, $path, $params) {
    $res = $this->http->request($method, $path, $params);
    if ($res->getStatusCode() == 200) {
      $body = $res->getBody();
      $json = json_decode($body);
      return $json;
    }
    return null;
  }

  public function getRequest($path, $params = null) {
    if ($params == null) {
      $params = array();
    }
    $params = array_merge($this->getDefaultQueryParams(), $params);
    $params = ['query' => $params];
    return $this->request('GET', $path, $params);
  }

  public function putRequest($path, $params = null) {
    if ($params == null) {
      $params = array();
    }
    $params = ['query' => $this->getDefaultQueryParams(), 'multipart' => $params];
    return $this->request('PUT', $path, $params);
  }

  public function postRequest($path, $params = null) {
    if ($params == null) {
      $params = array();
    }
    $params = ['query' => $this->getDefaultQueryParams(), 'form_params' => $params];
    return $this->request('POST', $path, $params);
  }

  private $topics;

  public function topics() {
    if($this->topics == null) {
      $this->topics = new Topics($this);
    }
    return $this->topics;
  }

  public function topic($id) {
    $topic = new Topic($this, $id);
    return $topic;
  }

  private $categories;

  public function categories() {
    if($this->categories == null) {
      $this->categories = new Categories($this);
    }
    return $this->categories;
  }
  
  public function category($id)
  {
      $category = new Category($this, $id);
      return $category;
  }

  private $posts;

  public function posts() {
    if($this->posts == null) {
      $this->posts = new Posts($this);
    }
    return $this->posts;
  }
  
  public function user($id)
  {
      $user = new User($this, $id);
      return $user;
  }

  private $users;

  public function users()
  {
      if ($this->users == null) {
          $this->users = new Users($this);
      }
      return $this->categories;
  }
  
}
