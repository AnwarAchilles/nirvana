<?php

/**
 * Class Auth
 *
 * This class handles authentication-related operations.
 */
class Auth extends BaseApi
{
  /**
   * This variable determines whether the system is in secure mode or not.
   * Set it to TRUE for secure mode, and FALSE for non-secure mode.
   */
  public $secure = FALSE;

  /**
   * This array contains a list of forbidden actions.
   * These actions should not be accessible.
   */
  public $forbidden = [ 
    'list_REST',
    'show_REST',
    'create_REST',
    'update_REST',
    'delete_REST',
    'paginate_REST',
    'entries_REST',
  ];

  /**
   * Perform user login and generate a JWT token.
   *
   * @Request: POST
   */
  public function login_POST()
  {
    // Check if a user with the provided name exists
    $check = $this->models->where('name', $this->method('name'));

    if ($check->count_rows()) {
      // Generate a JWT token for the user
      $this->data = $this->generateJwt($check->get());
      $this->return(200, "Success");
    } else {
      $this->return(400, "Failed");
    }
  }
}