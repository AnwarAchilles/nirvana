<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Product extends CyruzController
{
  # index page
  public function index()
  {
    // set layout
    $this->data['layout'] = [
      'draw'=> true,
      'module'=> 'cyruz',
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'Product', 'index' ],
      'title'=> 'Product And Display',
    ];
    $this->data['table'] = [
      'header'=> 'Product And Display',
      'description'=> 'Todo management user for this website',
    ];

    // render
    $this->layout( $this->data );
  }

  
  # report print
  public function print( $id )
  {
    // set layout
    $this->data['layout'] = [
      'module'=> 'cyruz',
      'layout'=> 'Cyruz/layout-report',
      'source'=> [ 'Cyruz', 'Product', 'report', 'print' ],
      'title'=> 'sample',
    ];
    // set report pdf
    $this->data['report'] = [
      'type'=> 'PDF',
      'download'=> $this->input->get('download'),
      'filename'=> 'Nirvana-Print-Product',
      'paper'=> 'A4',
      'direction'=> 'portrait',
    ];
    // get data
    $this->data['data'] = $this->api("GET", "product/".$id);
    // render
    $this->report( $this->data );
  }


  # report format
  public function format()
  {
    // set layout
    $this->data['layout'] = [
      'source'=> [ 'Cyruz', 'Product', 'report', 'format' ],
    ];
    // set report
    $this->data['report'] = [
      'type'=> 'EXCEL',
      'filename'=> 'Nirvana-Format-Product',
    ];
    // render
    $this->report( $this->data );
  }


  # report import
  public function import()
  {
    // set report
    $this->data['report'] = [
      'type'=> 'EXCEL',
      'file'=> $_FILES['excel'],
    ];
    // report return excel
    $excel = $this->report( $this->data );
    // insertt to database
    foreach ($excel as $data) {
      // set query
      $query = [
        'category'=> $data['Category'],
        'name'=> $data['Name'],
        'content'=> $data['Content'],
      ];
      // send to api
      $this->api('POST', 'product', $query);
    }
  }


  # report export
  public function export()
  {
    // set layout
    $this->data['layout'] = [
      'source'=> [ 'Cyruz', 'Product', 'report', 'export' ],
    ];
    // set report
    $this->data['report'] = [
      'type'=> 'EXCEL',
      'filename'=> 'Nirvana-Export-Product',
    ];
    // load data
    $this->data['data'] = $this->api("GET", "product/list");
    // render
    $this->report( $this->data );
  }
}