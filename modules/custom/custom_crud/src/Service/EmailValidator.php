<?php

namespace Drupal\custom_crud\Service;

/**
 * Provides email validation service.
 */
class EmailValidator {

  /**
   * Validates if the provided email address is valid.
   *
   * @param string $email
   *   The email address to validate.
   *
   * @return bool
   *   TRUE if the email address is valid, FALSE otherwise.
   */
  public function isValidEmail(string $email): bool {
    // Use PHP's built-in filter to validate email.
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
  }
}
