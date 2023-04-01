<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Dashboard extends CyruzController
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
    $this->data['layout']['title'] = 'Dashboard';
    $this->data['layout']['source'] = [ 'Cyruz', 'Dashboard', 'index' ];

    // list card
    $this->data['listcard'] = [
      'user'=> 3, 'menu'=> 4, 'role'=> 5, 'storage'=> 6,
    ];
    
    // set total count 0
    $this->data['totalcount'] = 0;
    
    // loop list card
    foreach ($this->data['listcard'] as $name=>$id) {
      $this->data['dataset'][ $name ] = $this->api('GET', 'menu/'.$id);
      $this->data['datacount'][ $name ] = $this->api('GET', $name.'/count') ['count'];
      $this->data['totalcount'] += $this->data['datacount'][ $name ];
    }

    // get data toast
    $this->data['toasted'] = $this->api("GET", "toasted/list", [
      "Q[order_by][id_toasted]"=> "desc",
      "Q[limit]"=> 5,
    ]);

    // output layout
    $this->layout($this->data);
  }

  # todo reset toast
  public function resetToasted() {
    $this->db->query("TRUNCATE toasted");
    $this->api('POST', 'toasted', [
      "header"=> "All Notifications Clearing",
      "message"=> "Thanks for click",
      "icon"=> "warning",
    ]);
    redirect(base_url("cyruz/dashboard"));
  }

}