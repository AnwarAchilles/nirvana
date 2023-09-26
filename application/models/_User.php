<?php



class _User extends CoreModel
{

  public $table = 'user';

  public $primary_key = 'id_user';

  public $has_one = [];

  public $paginate = 5;
  
}