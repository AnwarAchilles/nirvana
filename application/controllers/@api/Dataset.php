<?php
defined('BASEPATH') OR exit('No direct script access allowed');



/**
 * Class Dataset
 *
 * This class represents a custom API controller that extends CoreApi.
 *
 * @class Dataset
 * @extends CoreApi
 */
class Dataset extends BaseApi
{

  /**
   * This variable determines whether the system is in secure mode or not.
   * Set it to TRUE for secure mode, and FALSE for non-secure mode.
   */
  public $secure = FALSE;

  /**
   * This array contains a list of forbidden actions.
   * These actions should not be accessible.
   */
  public $forbidden = [ 
    'list_REST',
    'show_REST',
  ];


  /**
   * Constructor for the Dataset class.
   * 
   * You can perform additional setup and logic here.
   */
  public function __construct() {
    parent::__construct();

    // Add your initialization code here
  }

  public function load_GET() {
    $this->data['code'] = 
    str_replace('   ', "    ", 
    json_encode($this->dataset($this->id), JSON_PRETTY_PRINT));
    $this->return(200, 'Success');
  }

  public function save_POST() {
    file_put_contents(PATH_ROOT.'/dataset/'.$this->method('name').'.json', json_encode($this->method('code'), JSON_PRETTY_PRINT));
    $this->return(200, 'Success Update Dataset');
  }


}