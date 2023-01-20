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

    
    $this->data['count']['user'] = $this->api("GET", "user/count")[0];
    $this->data['count']['menu'] = $this->api("GET", "menu/count")[0];
    $this->data['count']['role'] = $this->api("GET", "role/count")[0];
    $this->data['count']['product'] = $this->api("GET", "product/count")[0];
    
    $totalCount = 0;
    $totalCount += $this->data['count']['user'];
    $totalCount += $this->data['count']['menu'];
    $totalCount += $this->data['count']['role'];
    $totalCount += $this->data['count']['product'];
    
    $this->data['totalCount'] = $totalCount;

    $totalCountDescription = 'This is summary text from each assets, ';
    $totalCountDescription = $totalCountDescription.'total from User (' . $this->data['count']['user'] . ') ';
    $totalCountDescription = $totalCountDescription.'total from Menu (' . $this->data['count']['menu'] . ') ';
    $totalCountDescription = $totalCountDescription.'total from Role (' . $this->data['count']['role'] . ') ';
    $totalCountDescription = $totalCountDescription.'total from product (' . $this->data['count']['product'] . ') ';
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