<?php


use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class BaseApi
 *
 * This class provides a base structure for handling RESTful API operations.
 *
 * It extends the CoreApi class, which likely contains common functionalities
 * and methods needed for API operations.
 */
class BaseApi extends CoreApi
{
  /**
   * Constructor for the BaseApi class.
   *
   * It calls the constructor of the parent class (CoreApi) to initialize
   * any necessary resources or configurations.
   */
  public function __construct()
  {
    parent::__construct();

    if ($this->decodeJwt()) {
      $this->inTenant = $this->decodeJwt()->data;
      
      if (isset($this->inTenant->id_tenant)) {
        $this->query['where'] = [ 'id_tenant'=> $this->inTenant->id_tenant ];
  
        if (isset($this->models->cache_prefix)) {
          $this->models->cache_prefix =  $this->inTenant->serial;
        }
  
        // load country config
        $this->config->load('country/'.strtolower($this->inTenant->country).'.php');
        $country = $this->config->item('country');
        
        // set timezone
        date_default_timezone_set($country['information']['timezone']);
      }
    }
  }

  public function recache_GET()
  {
    if ($this->id) {
      $isRecache = $this->models->apiRecache($this->id);
      if ($isRecache) {
        $this->return(200, "Success");
      } else {
        $this->return(400, "Failed");
      }
    } else {
      $this->return(400, "ID required");
    }
  }

  public function uncache_GET()
  {
    $isRecache = $this->models->apiUncache();
    if ($isRecache) {
      $this->return(200, "Success");
    } else {
      $this->return(400, "Failed");
    }
  }

  public function dataset_GET()
  {
    $this->data['total'] = $this->models->fields()
      ->where('id_tenant', $this->inTenant->id_tenant)
      ->count_rows();
    $this->return(200);
  }
  

  protected function dataset( $filename, $country='' ) {
    $pathfile = PATH_ROOT.'/dataset/'.$country.'/'.$filename.'.json';
    if (file_get_contents($pathfile)) {
      return json_decode(file_get_contents($pathfile), true);
    }
  }

  protected function htmlToExcel( $filename, $html ) {
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
    $spreadsheet = $reader->loadFromString($html);
    // Assuming $filename contains the full path to the desired location and the desired filename
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($filename);
  }

  protected function htmlTable($header, $data) {
    $table = [];
    $table[] = '<table>';
    $table[] = '<thead>';
    $table[] = '<tr>';
    foreach ($header as $row) {
      $table[] = '<th>'.$row.'</th>';  
    }
    $table[] = '</tr>';
    $table[] = '</thead>';
    $table[] = '<tbody>';
    foreach ($data as $datalist) {
      $table[] = '<tr>';
      foreach ($datalist as $row) {
        $table[] = '<td>'.$row.'</td>';
      }
      $table[] = '</tr>'; 
    }
    $table[] = '</tbody>';
    $table[] = '</table>';

    return implode("\n", $table);
  }
}
