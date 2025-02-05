<?php
namespace Drupal\trainig_user\Controller;
use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for custom routes.
 */
class CustomController extends ControllerBase {

  /**
   * Returns a custom page.
   */
  public function content() {
    return [
      '#markup' => $this->t('Welcome to the custom page!'),
    ];
  }

}
