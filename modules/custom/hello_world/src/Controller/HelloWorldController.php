<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides the "Hello World" page.
 */
class HelloWorldController extends ControllerBase {
  /**
   * Returns a simple "Hello World" message.
   */
  public function content() {
    return [
      '#markup' => $this->t('Hello, World! Welcome to Drupal 10.'),
    ];
  }
}
