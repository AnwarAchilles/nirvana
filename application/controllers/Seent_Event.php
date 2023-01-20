<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seent_Event extends BaseController
{
  /* todo live toasted */
  public function toasted() {
    date_default_timezone_set("asia/jakarta");

    $data = $this->api("GET", "toasted", [
      "Q[limit]"=> 1,
      "Q[order_by][id_toasted]"=> 'DESC',
    ]);
    if (!empty($data)) {
      $data = $data[0];
    }
    
    $sec = date("s");
    if ($sec>=4) {
      $sec = $sec - 4;
    }
    $datatimeParse = date("YmdHi".$sec);

    $dateCheck = (int) date_format(date_create($data['datetime']), "YmdHis");
    $dateStamp = (int) date_format(date_create($datatimeParse), "YmdHis");

    header("Cache-Control: no-store");
    header("Content-Type: text/event-stream");

    if ($dateCheck>$dateStamp) {
      $parse = json_encode($data);
      echo "event: success\n";
      echo "data: {$parse}\n\n";
    }else {
      echo "event: failed\n";
      echo "data: 0\n\n";
    }

    flush();
  }
}