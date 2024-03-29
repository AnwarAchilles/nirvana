<?php
defined('BASEPATH') OR exit('No direct script access allowed');


use chriskacerguis\RestServer\RestController;

use Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use \Firebase\JWT\ExpiredException;


/**
 * Class CoreApi
 *
 * This is the core class for your API, incorporating several essential features.
 * This class manages JWT authentication, data manipulation, and model setup.
 */
class CoreApi extends RestController
{

  /* ---- ---- ---- ----
   * MAIN VARIABLES
   * ---- ---- ---- ---- */

  // Models and Mod variable
  public $models, $mod;

  // Index and ID variables
  public $index, $id;

  // Query and Que variables
  public $query, $que;

  // Method and HTTP variables
  public $method, $http;

  // Files and File variables
  public $files, $file;

  // Output and Out variables
  public $output, $out;

  // Return variable for API responses
  public $return;

  // Development and Dev variables
  public $development, $dev;

  // Data variable for API data
  public $data;

  // Secure variable to determine if API requests are secure
  public $secure = TRUE;

  // Token variable to store JWT tokens
  public $token;

  public $state = [
    'USE_ID'=> TRUE,
  ];

  // Version directory
  public $version;

  // Add forbidden access to some class
  public $forbidden = [];




  /* ---- ---- ---- ----
   * MAIN CONSTRUCTOR
   * ---- ---- ---- ---- */


  /**
   * Constructor for the CoreApi class.
   *
   * This function is executed when creating an instance of the CoreApi class.
   * It loads configuration, validates JWT, and sets up models.
   */
  public function __construct() {
    parent::__construct();

    // Load configuration
    $this->config->load('core');
    $this->config->api = $this->config->item('api');

    $this->load->driver('cache');

    session_write_close();
    
    $this->dev = [];
    $this->development = [];
    $this->return = [];
    
    self::_cors();
    self::_validate_jwt();
    self::_setup_models();
    self::_setup_index();
    self::_setup_method();
    self::_validate_formdata();

    // Populate development information
    self::_development();
  }



  /* ---- ---- ---- ----
   * PRIVATE METHODS
   * ---- ---- ---- ---- */

  /**
   * Set up models based on URI segments.
   *
   * This function checks the URI segments to determine the model to load dynamically.
   * If the model exists, it loads the model and assigns it to the $mod property.
   */
  private function _setup_models()
  {
    try {
      $segments = $this->uri->segments;
      $versioning = false;
      
      if (file_exists(PATH_APPLICATION.'/models/'.strtolower($segments[2]).'/')) {
        $versioning = true;
      }
      
      if ($versioning) {
        // Check if the segment containing the model name exists
        $models = (isset($segments[3])) ? $segments[3] : '';
      }else {
        // Check if the segment containing the model name exists
        $models = (isset($segments[2])) ? $segments[2] : '';
      }
      
      // Load the model dynamically if the model name is not empty
      if (!empty($models)) {
        if ($versioning) {
          // Check if the model file exists
          $modelFilePath = APPPATH . '/models/'.$segments[2].'/_' . ucfirst($models) . '.php';
        }else {
          // Check if the model file exists
          $modelFilePath = APPPATH . '/models/_' . ucfirst($models) . '.php';
        }
        if (!file_exists($modelFilePath)) {
          // Return a 404 response if the model file is not found
          $this->development['[+]-Model'] = 'Model undefined';
        } else {
          $this->development['[+]-Model'] = [];
          if ($versioning) {
            $file = $segments[2].'/_' . ucfirst($models);
            // Load the model and assign it to $mod
            $this->load->model($segments[2].'/_' . ucfirst($models), 'models');
          }else {
            $file = '_' . ucfirst($models);
            // Load the model and assign it to $mod
            $this->load->model('_' . ucfirst($models), 'models');
          }
          $this->development['[+]-Model']['file'] = $file;
          $this->development['[+]-Model']['table'] = (isset($this->models->table)) ? $this->models->table : '';
          $this->development['[+]-Model']['primary_key'] = (isset($this->models->primary_key)) ? $this->models->primary_key : '';
          $this->development['[+]-Model']['paginate'] = (isset($this->models->paginate)) ? $this->models->paginate : false;
          $this->development['[+]-Model']['has_one'] = (isset($this->models->has_one)) ? $this->models->has_one : [];
          $this->development['[+]-Model']['belongs_to'] = (isset($this->models->belongs_to)) ? $this->models->belongs_to : [];
          $this->development['[+]-Model']['has_many'] = (isset($this->models->has_many)) ? $this->models->has_many : [];
          $this->mod = $this->models;
        }
      }
      // if ($this->models) {
      // }
    } catch (Exception $e) {
      // Handle exceptions if needed
    }
  }


  /**
   * Set up the index/id based on URI segments.
   *
   * This function parses the URI segments to determine the index/id for the API operation.
   * It checks if the requested HTTP method (e.g., GET, POST, PUT, DELETE, PATCH) exists as a method in the current class.
   * If the method exists, it sets the index to the next segment in the URI.
   */
  private function _setup_index()
  {
    try {
      $segments = $this->uri->segments;
      $versioning = false;
      
      if (file_exists(PATH_APPLICATION.'/models/'.strtolower($segments[2]).'/')) {
        $versioning = true;
      }

      // Initialize the index/id
      $index = '';
      if ($versioning) {
        if (isset($segments[4])) {
          // Check if the requested method exists
          if (
            method_exists($this, $segments[4] . '_GET') ||
            method_exists($this, $segments[4] . '_POST') ||
            method_exists($this, $segments[4] . '_PUT') ||
            method_exists($this, $segments[4] . '_DELETE') ||
            method_exists($this, $segments[4] . '_PATCH')
          ) {
            $index = (isset($segments[5])) ? $segments[5] : '';
          } else {
            $index = (isset($segments[4])) ? $segments[4] : '';
          }
        }
      }else {
        if (isset($segments[3])) {
          // Check if the requested method exists
          if (
            method_exists($this, $segments[3] . '_GET') ||
            method_exists($this, $segments[3] . '_POST') ||
            method_exists($this, $segments[3] . '_PUT') ||
            method_exists($this, $segments[3] . '_DELETE') ||
            method_exists($this, $segments[3] . '_PATCH')
          ) {
            $index = (isset($segments[4])) ? $segments[4] : '';
          } else {
            $index = (isset($segments[3])) ? $segments[3] : '';
          }
        }
      }
      // Convert the index to either an integer or a string
      $index = (is_numeric($index)) ? (int) $index : (string) $index;
      // Assign the index/id to class properties
      $this->index = $index;
      $this->id = $index;
    } catch (Exception $e) {
      // Handle exceptions if needed
    }
  }


  /**
   * Set up the HTTP request method data.
   *
   * This function collects data from various HTTP request methods (GET, POST, PUT, DELETE, PATCH, OPTIONS).
   * It also handles query parameters and file uploads.
   */
  private function _setup_method()
  {
    try {
      // Merge data from different HTTP request methods into $this->method
      $this->method = array_merge(
        $this->query(),
        $this->post(),
        $this->put(),
        $this->delete(),
        $this->patch(),
        $this->options()
      );

      // Handle query parameters
      $this->files = $_FILES;
      $this->file = $_FILES;
      
      // Check if 'Q' query parameter exists and move it to $this->query
      if (isset($this->method['Q'])) {
        $this->query = $this->method['Q'];
        unset($this->method['Q']);
      }

      // Check if 'QUERY' query parameter exists and move it to $this->query
      if (isset($this->method['QUERY'])) {
        $this->query = $this->method['QUERY'];
        unset($this->method['QUERY']);
      }
    } catch (Exception $e) {
      // Handle exceptions if needed
    }
  }


  /**
   * Validate and parse form data from the HTTP request.
   *
   * This function processes and validates form data received in the HTTP request.
   * It extracts form fields and their values and stores them in the $this->method property.
   */
  private function _validate_formdata()
  {
    try {
      $raw_data = '';
      
      // Concatenate all form data into a single string
      foreach ($this->method as $key => $val) {
        if (is_string($val)) {
          $raw_data .= $key . $val;
        }
      }

      // Check if the request contains form-data
      if (strpos($raw_data, "form-data")) {
        $data = [];
        
        // Extract the boundary
        $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));
        $parts = array_slice(explode($boundary, $raw_data), 1);
        
        foreach ($parts as $part) {
          // If this is the last part, break
          if ($part == "--\r\n") break;

          // Replace any mistakes in the form-data headers
          $part = str_replace("_form-data", " form-data", $part);
          $part = str_replace("_name", " name=", $part);

          // Separate content from headers
          $part = ltrim($part, "\r\n");
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
            preg_match('/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/', $headers['content-disposition'], $matches);

            list(, $type, $name) = $matches;

            // Check if the field contains a filename
            if (!strpos($headers['content-disposition'], "filename")) {
              $data[$name] = substr($body, 0, strlen($body) - 2);
            }
          }
        }
        
        // Update $this->method with the parsed form data
        $this->method = $data;
      }
    } catch (Exception $e) {
      // Handle exceptions if needed
    }
  }
  


  /**
   * Validate and decode JWT token from the Authorization header.
   *
   * This function validates the JWT token provided in the Authorization header.
   * It checks for the token's validity, expiration, and secret key.
   * If the token is valid, it stores the decoded token in the $this->token property.
   */
  private function _validate_jwt()
  {
    try {
      // Check if secure mode is enabled
      if ($this->secure) {
        $token = null;
        
        // Check if the 'Authorization' header exists
        if ($this->head('Authorization')) {
          $authHeader = $this->head('Authorization');
          $arr = explode(" ", $authHeader);

          if (isset($arr[1])) {
            $token = $arr[1];

            if ($token) {
              $headers = new stdClass();
              
              // Decode the JWT token
              $decoded = JWT::decode($token, new Key($this->config->api['token_secretkey'], 'HS256'), $headers);

              if ($decoded) {
                // Check if the token's secret key matches the expected secret key
                if ($decoded->secretkey !== $this->config->api['token_secretkey']) {
                  $this->return(400, 'TOKEN SECRET KEY FAILED');
                }

                // Check if the token has expired
                if (time() > $decoded->expired) {
                  return $this->return(400, 'TOKEN EXPIRED');
                }

                // Store the decoded token in $this->token
                $this->token = $decoded;
              } else {
                $this->return(400, 'Failed Decode JWT');
              }
            } else {
              $this->return(400, 'NO TOKEN SENT');
            }
          } else {
            $this->return(400, 'NO TOKEN SENT');
          }
        } else {
          $this->return(400, 'NO AUTHORIZATION HEADER SENT');
        }
      }
    } catch (Exception $e) {
      // Handle exceptions if needed
    }
  }

  /**
   *  An example CORS-compliant method.  It will allow any GET, POST, or OPTIONS requests from any
   *  origin.
   *
   *  In a production environment, you probably want to be more restrictive, but this gives you
   *  the general idea of what is involved.  For the nitty-gritty low-down, read:
   *
   *  - https://developer.mozilla.org/en/HTTP_access_control
   *  - https://fetch.spec.whatwg.org/#http-cors-protocol
   *
   */
  private function _cors() { 
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
  }

  /**
   * Set the development state for debugging purposes.
   *
   * This function sets the development state by gathering various information for debugging.
   * It collects data such as the endpoint, request method, HTTP method, query parameters, and token.
   * The collected data is stored in the $this->development property for debugging purposes.
   */
  private function _development()
  {
    // Set the development state
    $this->development = [
      '[+]-Endpoint' => $_SERVER['QUERY_STRING'],
      '[+]-Request' => $_SERVER['REQUEST_METHOD'],
      '[+]-Method' => $this->method,
      '[+]-Query' => $this->query,
      '[+]-Token' => $this->token,
    ];

    // Assign the development data to $this->dev for convenience
    $this->dev = $this->development;
  }

  /**
   * Check if the given method name is forbidden.
   *
   * @param string $methoName The name of the method to check.
   * @throws Some_Exception_Class Description of exception
   * @return void
   */
  private function forbidden( $methodName )
  {
    // dd($this->forbidden);
    if (in_array($methodName, $this->forbidden)) {
      $this->return(403, 'Endpoint Has Forbidden');
    }
  }









  /* ---- ---- ---- ----
   * PUBLIC METHODS
   * ---- ---- ---- ---- */


  /**
   * Generate a JWT token with provided data and expiration time.
   *
   * This function generates a JWT token with the provided data and an optional expiration time.
   * It encodes the token using the secret key specified in the configuration.
   *
   * @param array $data       The data to be included in the token.
   * @param int   $expired_second (Optional) The expiration time for the token in seconds. Default is 3600 seconds (1 hour).
   *
   * @return array An array containing the generated token and its expiration timestamp.
   */
  public function generateJwt($data, $expired_second = 3600)
  {
    $expired = time() + $expired_second;
    $token = [
      "secretkey" => $this->config->api['token_secretkey'],
      "expired" => $expired,
      "data" => $data,
    ];

    // Encode the token using the specified secret key and algorithm (HS256)
    $response = JWT::encode($token, $this->config->api['token_secretkey'], 'HS256');

    // Return an array with the generated token and its expiration timestamp
    return ['token' => $response, 'expired' => $expired];
  }


  /**
   * Decode and validate a JWT token from the 'Authorization' header.
   *
   * This function decodes and validates a JWT token provided in the 'Authorization' header.
   * It checks for the token's validity and uses the secret key specified in the configuration for decoding.
   *
   * @return stdClass|array Returns the decoded token as an stdClass object if valid; otherwise, an empty array.
   */
  public function decodeJwt()
  {
    $token = null;

    // Check if the 'Authorization' header exists
    if ($this->head('Authorization')) {
      $response = explode(" ", $this->head('Authorization'));
      $token = $response[1];
      $headers = new stdClass();

      // Decode the JWT token using the specified secret key and algorithm (HS256)
      return JWT::decode($token, new Key($this->config->api['token_secretkey'], 'HS256'), $headers);
    } else {
      // Return an empty array if no 'Authorization' header is found
      return [];
    }
  }

  /**
   * Generate and inject a serial number into the provided data if 'serial' is set in the request.
   *
   * This function checks if 'serial' is set in the request method data.
   * If 'serial' is present, it generates a unique serial number and injects it into the provided data.
   *
   * @param array $injection The data to which the serial number will be injected.
   *
   * @return array The modified data with the serial number injected, or the original data if 'serial' is not set.
   */
  public function serialNumber( $serial='', $length=24, $injection='' )
  {
    $useSerial = (isset($this->models->serial)) ? $this->models->serial : $serial;
    $usePrefix = (isset($this->models->serial_prefix)) ? $this->models->serial_prefix : '-';
    $useLength = (isset($this->models->serial_length)) ? $this->models->serial_length : $length;

    if (!empty($injection)) {
      if ($useSerial!=='') {
        // Generate a unique serial number and inject it into the provided data
        $injection['serial'] = strtoupper($useSerial . $usePrefix . new Visus\Cuid2\Cuid2($useLength));
      }
      // Return the modified data with the serial number injected, or the original data
      return $injection;
    }else {
      return strtoupper($useSerial . $usePrefix . new Visus\Cuid2\Cuid2($useLength));
    }
  }

  /**
   * Generate and send an API response.
   *
   * This function generates and sends an API response based on the provided status code and message.
   * It also includes development information when in non-production mode and handles data.
   *
   * @param int  $status  The HTTP status code for the response. Default is 203 (Non-Authoritative Information).
   * @param string $message (Optional) The message to include in the response.
   */
  public function return($status = 203, $message = null)
  {
    // Initialize the response array
    $return = [];

    // Include development information in non-production mode
    if ($this->config->api['mode'] !== 'production') {
      $return['development'] = array_merge($this->development, $this->dev);
    }

    // Set the HTTP status code
    $return['status'] = $status;

    // Include a message in the response if provided
    if (!empty($message)) {
      $return['message'] = $message;
    }

    // Merge additional data with the response if it's an array
    if (is_array($this->return)) {
      $return = array_merge($return, $this->return);
    } else {
      $return = $this->return;
    }

    // Convert $this->data to an array if it's not already
    $this->data = (array)$this->data;

    // Include data in the response if available
    if (count($this->data) !== 0) {
      $return['data'] = $this->data;
    } else {
      $return['data'] = [];
    }

    // Send the response using the RestController's response method
    $this->response($return, $status, false);
  }


  /**
   * Get a specific request method or retrieve all request methods.
   *
   * This function allows you to retrieve a specific request method's data by providing its name,
   * or it can return all request methods if no specific name is provided.
   *
   * @param string $name (Optional) The name of the request method to retrieve.
   *
   * @return mixed|null|array Returns the requested request method's data if found, or all request methods if no name is provided.
   *            Returns null if the requested name does not exist.
   */
  public function method($name = "")
  {
    if (!empty($name)) {
      // Check if the requested request method exists
      if (isset($this->method[$name])) {
        return $this->method[$name];
      } else {
        return null; // Return null if the requested name does not exist
      }
    } else {
      // Return all request methods if no specific name is provided
      return $this->method;
    }
  }




  /* ---- ---- ---- ----
   * REST MAPING
   * ---- ---- ---- ---- */


  /**
   * List resources.
   *
   * @request: GET
   * @endpoint: /api/table
   */
  public function list_REST()
  {
    $this->forbidden(__FUNCTION__);

    $this->models->builder($this->query);
    $this->models->relation();
    $this->data = $this->models->apiGetAll();
    $this->return['total'] = $this->models->apiCountRows();

    if ($this->data) {
      $this->return(200, "Success");
    } else {
      $this->return(400, "Failed");
    }
  }

  /**
   * Show a single resource.
   *
   * @request: GET
   * @endpoint: /api/table/{id}
   */
  public function show_REST()
  {
    $this->forbidden(__FUNCTION__);

    $this->models->builder($this->query);
    $this->models->relation();
    $this->data = $this->models->apiGet($this->id);

    if ($this->data) {
      $this->return(200, "Success");
    } else {
      $this->return(400, "Failed");
    }
  }

  /**
   * Create a new resource.
   *
   * @request: GET, POST
   * @endpoint: /api/table
   */
  public function create_REST()
  {
    $this->forbidden(__FUNCTION__);

    $this->models->builder($this->query);
    $isCreate = $this->models->apiCreate($this->serialNumber('', 12, $this->method));
    $this->data[$this->models->primary_key] = $isCreate;

    if ($isCreate) {
      $this->return(200, "Success");
    } else {
      $this->return(400, "Failed");
    }
  }

  /**
   * Update an existing resource.
   *
   * @request: GET, POST, PUT
   * @endpoint: /api/table/{id}
   */
  public function update_REST()
  {
    $this->forbidden(__FUNCTION__);

    if ($this->id) {
      $this->models->builder($this->query);

      if ($this->state['USE_ID']) {
        $isUpdate = $this->models->apiUpdate($this->method, $this->id);
      }else {
        $isUpdate = $this->models->apiUpdate($this->method);
      }

      if ($isUpdate) {
        $this->models->builder($this->query);
        $this->models->relation();
        $this->data = $this->models->apiGet($this->id);

        $this->return(200, "Success");
      } else {
        $this->return(400, "Failed");
      }
    } else {
      $this->return(400, "ID required");
    }
  }

  /**
   * Delete a resource.
   *
   * @request: GET, POST, DELETE
   * @endpoint: /api/table/{id}
   */
  public function delete_REST()
  {
    $this->forbidden(__FUNCTION__);

    if ($this->id) {
      $isDelete = $this->models
        ->builder($this->query)
        ->apiDelete($this->id);
      if ($isDelete) {
        $this->return(200, "Success");
      } else {
        $this->return(400, "Failed");
      }
    } else {
      $this->return(400, "ID required");
    }
  }

  /**
   * Paginate resources.
   *
   * @request: GET
   * @endpoint: /api/table/paginate
   */
  public function paginate_REST()
  {
    $this->forbidden(__FUNCTION__);

    $this->models->builder($this->query);
    $current = (empty($this->id)) ? 1 : $this->id;
    $total = $this->models->apiCountRows();
    if(isset($this->models->paginate)) {
      $slice = $this->models->paginate;
    }else {
      $slice = $total;
    }
    $this->return['total'] = $total;
    $this->return['paginate']['current'] = $current;
    $this->return['paginate']['total'] = ceil($total / $slice);
    $this->return['paginate']['slice'] = $slice;
    $this->models->builder($this->query);
    $this->models->relation();
    $this->data = $this->models->apiPaginate($slice, $current, $total);

    $this->return(200);
  }

  /**
   * Handle resource entries.
   *
   * @request: POST
   * @endpoint: /api/table/entries
   */
  public function entries_REST()
  {
    $this->forbidden(__FUNCTION__);
    
    $this->models->builder($this->query);
    $isCreate = $this->models->apiEntries($this->serialNumber($this->method));
    $this->data[$this->models->primary_key] = $isCreate;

    if ($isCreate) {
      $this->return(200, "Success");
    } else {
      $this->return(400, "Failed");
    }
  }


  /* ---- ---- ---- ----
   * METHOD MAPING
   * ---- ---- ---- ---- */


  /**
   * Method Mapping
   *
   * These methods map HTTP methods to corresponding RESTful actions.
   * They ensure proper handling of API requests based on HTTP methods.
   */

  // Method Mapping for GET Requests
  public function index_GET()
  {
    if (empty($this->index)) {
      $this->list_REST();
    } else {
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

  // Method Mapping for POST Requests
  public function list_POST()
  {
    $this->list_REST();
  }
  public function show_POST()
  {
    $this->show_REST();
  }
  public function create_POST()
  {
    $this->create_REST();
  }
  public function index_POST()
  {
    $this->create_REST();
  }

  // Method Mapping for PUT and PATCH Requests
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

  // Method Mapping for DELETE Requests
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

  // Method Mapping for PAGINATE Requests
  public function paginate_GET()
  {
    $this->paginate_REST();
  }

  // Method Mapping for ENTRIES Requests
  public function entries_GET()
  {
    $this->entries_REST();
  }
  public function entries_POST()
  {
    $this->entries_REST();
  }



  /**
   * Custom Endpoint
   *
   * This method handles custom API endpoints and returns a response.
   */
  public function costum_GET()
  {
    $this->return[] = 'api custom';
    $this->return(200);
  }

}