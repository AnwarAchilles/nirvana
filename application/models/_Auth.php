<?php



class _Auth extends CoreEloquent
{

  public $table = 'user';

  public $primary_key = 'id_user';

  public $has_one = [];
  
}