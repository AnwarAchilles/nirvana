<?php


require_once PATH_APPLICATION.'/third_party/Philsturgeon_Curl.php';


class Request {

  private $Version = "2.0";

  private $Codeigniter;

  private $Curl;

  private $Configure = [];


  public function __construct() {
    $this->Codeigniter =& get_instance();
    $this->Curl = new Philsturgeon_Curl();

    $this->Codeigniter->config->load('request');
    $this->Configure = $this->Codeigniter->config->item('request');
  }




  public function use( $name, $value ) {
    if (is_array($value)) {
      $this->Configure[$name] = array_merge_recursive($this->Configure[$name], $value);
    }else {
      $this->Configure[$name] = $value;
    }
    return $this;
  }


  public function init( $method, $url, $params=[] ) {
    $this->_targetUrl( $url );
    $this->_tokenJwt();
    $this->Curl->option(CURLOPT_HTTPHEADER, $this->Configure['headers']);
    $methodName = strtolower($method);
    if (count($params)) {
      $this->Curl->$methodName( $params );
    }else {
      $this->Curl->$methodName();
    }
    return $this->_response( $this->Curl->execute() );
  }


  public function get( $url, $params=[] )
  {
    $setParams = '';
    if (!empty($params)) {
      $setParams = '?'.$this->_arrayToGetParams($params);
    }
    return $this->init('GET', $url.$setParams, $params);
  }

  public function post( $url, $params=[] )
  {
    return $this->init('POST', $url, $params);
  }

  public function put( $url, $params=[] )
  {
    return $this->init('PUT', $url, $params);
  }

  public function delete( $url, $params=[] )
  {
    return $this->init('delete', $url, $params);
  }





  private function _targetUrl( $Url )
  {
    $Target = $this->Configure['url'];
    if ( (strpos($Url,'http')!==0) AND (strpos($Url,'https')!==0) ) {
      $Target = $Target.'/'.$Url;
    }else {
      $Target = $Url;
    }

    $this->Curl->create( $Target );
  }

  private function _tokenJwt()
  {
    if (!empty($this->Configure['token'])) {
      $this->use('headers', [
        'Authorization: Bearer '.$this->Configure['token'],
      ]);
    }
  }
  private function _response( $process )
  {
    if ($process) {
      return (object) [
        'status'=> $this->Curl->info['http_code'],
        'response'=> json_decode($process),
      ];
    }else {
      return (object) [
        'status'=> $this->Curl->info['http_code'],
        'response'=> $this->Curl->error_string,
        // 'information'=> $this->Curl->info,
      ];
    }
  }
  private function _arrayToGetParams($array, $prefix = '')
  {
    $params = array();
    foreach ($array as $key => $value) {
      $params[] = $prefix . urlencode($key) . "=" . urlencode($value);
    }
    return implode('&', $params);
  }

}