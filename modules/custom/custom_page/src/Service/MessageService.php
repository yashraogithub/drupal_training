<?php

namespace Drupal\custom_page\Service;

/**
 * Custom message service.
 */

class MessageService {

  /**
   * Returns a custom message.
   * 
   *  @return string
   *      The custom message.
   */
    public function printMessage() {
        $current_path = \Drupal::service('path.current')->getPath();
        return $current_path;7
    }
}