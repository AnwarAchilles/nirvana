<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Company extends CyruzController
{
  # main
  public function __construct()
  {
    parent::__construct();

    $this->data['layout']['draw'] = TRUE;
  }

  # index page
  public function index()
  {
    $this->data['layout']['source'] = [ 'Cyruz', 'Company', 'index' ];
    $this->data['layout']['title'] = 'Company Profile';

    $this->data['table'] = [
      'header'=> 'Company Profile',
      'description'=> 'Todo management user for this website',
    ];
    
    // set to company variable
    $this->data['company'] = $this->api('GET', 'company/latest');
    
    // check image logo
    if ( $this->data['company']['logo'] !== '0' ) {
      $storage = $this->api('GET', 'storage/'.$this->data['company']['logo']);
      if (count($storage) !== 0) {
        $this->data['company']['logo'] = images($storage['directory'].'/'.$storage['fullname']);
      }else {
        $this->data['company']['logo'] = images();
      }
    }else {
      $this->data['company']['logo'] = images();
    }
    
    // check if table is empty create new
    if ($this->api('GET', 'company/count')['count'] == 0) {
      // create data
      $this->api('POST', 'company', [
        'name'=> 'Company Name',
        'description'=> 'Company Description',
      ]);
      // reload page
      redirect('cyruz/company');
    }
    
    // output layout
    $this->layout( $this->data );
  }
}