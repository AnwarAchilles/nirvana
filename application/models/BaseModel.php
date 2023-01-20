<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Base Model
class BaseModel extends CoreModel
{
  /* ---- ---- ---- ----
   * MAIN VARIABLE FOR TABLE DATABASE
   * ---- ---- ---- ---- */
  public $table = 'user';

  
  /* ---- ---- ---- ----
   * MAIN CONSTRUCT
   * ---- ---- ---- ---- */
  public function __construct()
  {
    parent::__construct();

    # do something here
  }


  /* ---- ---- ---- ----
   * CREATE YOUR COSTUM METHOD HERE
   * ---- ---- ---- ---- */
  // public function costum() {}
}