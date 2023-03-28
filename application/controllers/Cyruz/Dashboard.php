<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Dashboard extends CyruzController
{
  // index page
  public function index()
  {
    $this->data['layout'] = [
      'module'=> 'cyruz',
      'draw'=> true,
      'layout'=> 'Cyruz/layout',
      'source'=> [ 'Cyruz', 'Dashboard', 'index' ],
      'title'=> 'Dashboard',
    ];

    $this->data['listcard'] = [
      'user'=> 3, 'menu'=> 4, 'role'=> 5, 'storage'=> 6,
    ];
    $this->data['totalcount'] = 0;
    foreach ($this->data['listcard'] as $name=>$id) {
      $this->data['dataset'][ $name ] = $this->api('GET', 'menu/'.$id);
      $this->data['datacount'][ $name ] = $this->api('GET', $name.'/count') ['count'];
      $this->data['totalcount'] += $this->data['datacount'][ $name ];
    }

    $totalCountDescription = 'This is summary text from each assets, ';
    $totalCountDescription = $totalCountDescription.'total from User (' . $this->data['datacount']['user'] . ') ';
    $totalCountDescription = $totalCountDescription.'total from Menu (' . $this->data['datacount']['menu'] . ') ';
    $totalCountDescription = $totalCountDescription.'total from Role (' . $this->data['datacount']['role'] . ') ';
    $totalCountDescription = $totalCountDescription.'total from Storage (' . $this->data['datacount']['storage'] . ') ';
    $this->data['totalCountDescription'] = $totalCountDescription;

    $this->data['toasted'] = $this->api("GET", "toasted/list", [
      "Q[order_by][id_toasted]"=> "desc",
      "Q[limit]"=> 5,
    ]);

    $this->layout($this->data);
  }


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