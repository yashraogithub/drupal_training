<?php

namespace Drupal\signup_login\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Logout' block.
 *
 * @Block(
 *   id = "logout_block",
 *   admin_label = @Translation("Logout Block"),
 * )
 */
class LogoutBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'link',
      '#title' => $this->t('Logout'),
      '#url' => \Drupal\Core\Url::fromRoute('user.logout'),
    ];
  }
}
