<?php

namespace Drupal\signup_login\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides a controller for the home page.
 */
class SignupLoginController extends ControllerBase {

  /**
   * Displays the home page.
   */
  public function home() {
    return [
      '#markup' => '<h1>Welcome to the Home Page!</h1>',
    ];
  }
}
