<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Welcome extends BaseController
{
  // index page
  public function index()
  {
    $data['layout'] = [
      'draw'=> FALSE,
      'source'=> [ 'Welcome', 'index' ],
      'title'=> 'Welcome To Nirvana',
    ];

    $this->layout($data);
  }

  // load content
  public function content( $name )
  {
    echo $this->load->view('Welcome/Content/'.$name.'.html', null, true);
  }

}