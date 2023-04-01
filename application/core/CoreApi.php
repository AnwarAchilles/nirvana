<?php


require APPPATH . '/third_party/Restful/Format.php';
require APPPATH . '/third_party/Restful/RestController.php';

# SET CORE API
class CoreApi extends RestController
{
  

  /* ---- ---- ---- ----
   * MAIN VARIABLE
   * ---- ---- ---- ---- */
  public $models, $mod;

  public $index, $id;

  public $query, $que;

  public $method, $http;

  public $files, $file;

  public $output, $out;

  public $return;

  public $development, $dev;

  public $data;

  public $payload;

  
  /* ---- ---- ---- ----
   * MAIN CONSTRUCT
   * ---- ---- ---- ---- */
  public function __construct()
  {
    parent::__construct();
    
    // load config
    $this->config->load('core');
    $this->config->api = $this->config->item('api');

    // reset
    $this->query=[];
    $this->data=[];
    $this->return=[];
    
    // load segments
    $segments = $this->uri->segments;

    // setup models
    $models = (isset($segments[2])) ? $segments[2] : '';

    // setup index/id
    $index='';
    if (isset($segments[3])) {
      if ( (method_exists($this, $segments[3].'_GET')) 
          OR (method_exists($this, $segments[3].'_POST'))
          OR (method_exists($this, $segments[3].'_PUT'))
          OR (method_exists($this, $segments[3].'_DELETE'))
          OR (method_exists($this, $segments[3].'_PATCH')) ) {
        $index = (isset($segments[4])) ? $segments[4] : '';
      }else {
        $index = (isset($segments[3])) ? $segments[3] : '';
      }
    }
    $index = (is_numeric($index)) ? (int) $index : (string) $index;

    // setup input/in
    $method = array_merge($this->query(), $this->post(), $this->put(), $this->delete(), $this->patch());
    $file = $_FILES;
    // set query
    if (isset($method['Q'])) {
      $this->query = $method['Q'];
      unset($method['Q']);
    }
    if (isset($method['QUERY'])) {
      $this->query = $method['QUERY'];
      unset($method['QUERY']);
    }

    // set to core api
    $this->method = $method; $this->http = $method;
    $this->files = $file; $this->file = $file;
    $this->index = $index; $this->id = $index;

    // validate formdata
    if ( ($this->put()) || ($this->patch()) ) {
      $this->_validate_formdata();
    }

    // load model dynamic
    if (!empty($models)) {
      if (!file_exists( APPPATH.'/models/M_'.ucfirst($models).'.php')) {
        $this->return(404, ['message'=>'model undefined']);
      }else {
        $this->load->model('M_'.ucfirst($models), 'models');
        $this->mod = $this->models;
      }
    }

    $this->controller = new CoreController();
  }

  /* ---- ---- ---- ----
   * PRIVATE METHOD
   * ---- ---- ---- ---- */
  public function _validate_formdata()
  {
    $raw_data='';
    foreach ($this->method as $key=>$val) {
      if (is_string($val)) {
        $raw_data = $raw_data . $key . $val;
      }
    }
    
    if (strpos($raw_data, "form-data")) {
      $data=[];

      $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));
      $parts = array_slice(explode($boundary, $raw_data), 1);

      foreach ($parts as $part) {
        // If this is the last part, break
        if ($part == "--\r\n") break;

        // replace mistake form-data
        $part = str_replace("_form-data", " form-data", $part);
        $part = str_replace("_name", " name=", $part);

        // Separate content from headers
        $part = ltrim($part, "\r\n");
        // $x=explode("\r\n\r\n", $part, 2);
        list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

        // Parse the headers list
        $raw_headers = explode("\r\n", $raw_headers);
        $headers = array();
        foreach ($raw_headers as $header) {
          list($name, $value) = explode(':', $header);
          $headers[strtolower($name)] = ltrim($value, ' '); 
        }
        
        // Parse the Content-Disposition to get the field name, etc.
        if (isset($headers['content-disposition'])) {
          
          preg_match( '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/', $headers['content-disposition'], $matches);

          list(, $type, $name) = $matches;
          
          if (!strpos($headers['content-disposition'], "filename")) {
            $data[$name] = substr($body, 0, strlen($body) - 2);
          }
        }
      }
    $this->method = $data;
    }
  }
  public function _development()
  {
    // set development state
    $this->development=[
      '[+]-Endpoint'=> $_SERVER['REDIRECT_QUERY_STRING'],
      '[+]-Request'=> $_SERVER['REQUEST_METHOD'],
      '[+]-Method'=> $this->method,
      '[+]-Query'=> $this->query,
    ];
    $this->dev=$this->development;
  }



  
  /* ---- ---- ---- ----
   * ACCESSABLE METHOD
   * ---- ---- ---- ---- */
  public function return( $status=203, $message=null )
  {
    $this->_development();
    if ($this->config->api['mode']!=='production') {
      $return['development'] = array_merge($this->development, $this->dev);
    }
    $return['status'] = $status;
    if (!empty($message)) {
      $return['message'] = $message;
    }
    $this->data = (array) $this->data;
    if (count($this->data)!==0) {
      $return['data'] = $this->data;
    }else {
      $return['data'] = [];
    }
    if (is_array($this->return)) {
      $return = array_merge($return, $this->return);
    }else {
      $return = $this->return;
    }

    $this->response($return, $status, false);
  }

  public function validate( $type, $data, $message=null )
  {
    $func = 'is_'.$type;
    if ( ! $func($data) ) {
      $this->return( 406, $message );
    }else {
      return true;
    }
  }

  public function method( $name )
  {
    if (isset($this->method[$name])) {
      return $this->method[$name];
    }else {
      return false;
    }
  }

  

  /* ---- ---- ---- ----
   * METHOD MAPING
   * ---- ---- ---- ---- */
  // method return
  public function index_GET()
  {
    if (empty($this->index)) {
      $this->list_REST();
    }else {
      $this->show_REST();
    }
  }
  public function list_GET()
  {
    $this->list_REST();
  }
  public function show_GET()
  {
    $this->show_REST();
  }
  public function list_POST()
  {
    $this->list_REST();
  }
  public function show_POST()
  {
    $this->show_REST();
  }

  // method create
  public function create_GET()
  {
    $this->create_REST();
  }
  public function create_POST()
  {
    $this->create_REST();
  }
  public function index_POST()
  {
    $this->create_REST();
  }

  // method update
  public function update_GET()
  {
    $this->update_REST();
  }
  public function update_POST()
  {
    $this->update_REST();
  }
  public function index_PUT()
  {
    $this->update_REST();
  }
  public function index_PATCH()
  {
    $this->update_REST();
  }
  public function update_PATCH()
  {
    $this->update_REST();
  }
  
  // method delete
  public function delete_GET()
  {
    $this->delete_REST();
  }
  public function delete_POST()
  {
    $this->delete_REST();
  }
  public function index_DELETE()
  {
    $this->delete_REST();
  }
  
  // costum endpoint
  public function costum_GET()
  {
    $this->return[] = 'api costum';
    $this->return(200);
  }






  // default endpoint
  public function endpoint() {
    $this->return(200, "NIRVANA API BUILDER");
  }
  public function endpoint_GET() { $this->endpoint(); }
  public function endpoint_POST() { $this->endpoint(); }
  public function endpoint_PUT() { $this->endpoint(); }
  public function endpoint_DELETE() { $this->endpoint(); }
  public function endpoint_PATCH() { $this->endpoint(); }

}