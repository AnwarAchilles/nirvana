<?php

/**
 * Class Auth
 *
 * This class handles authentication-related operations.
 */
class Auth extends BaseApi
{
  /**
   * Disable JWT authentication for this API.
   * @var bool
   */
  public $secure = FALSE;

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