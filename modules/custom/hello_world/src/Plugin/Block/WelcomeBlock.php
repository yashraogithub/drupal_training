<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Welcome Block'.
 *
 * @Block(
 *   id = "welcome_block",
 *   admin_label = @Translation("Welcome Block"),
 *   category = @Translation("Custom")
 * )
 */
class WelcomeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Welcome to Drupal 10 Yash!'),
    ];
  }

}
