<?php

namespace Drupal\student_crud\Service;

use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Database\Connection;

/**
 * Custom email validation service.
 */
class EmailValidationService {


    protected $emailValidationService;
    protected $database;
    /**
   * Constructs the form.
   */
  public function __construct(EmailValidatorInterface $emailValidationService,
  Connection $database,
  
  
  ) {
    $this->emailValidationService = $emailValidationService;
    $this->database = $database;

  }

    function isCustomValidEmail($email){ 
        return $this->emailValidationService->isValid($email);
    }

    function checkIfUserExist($email) {
            $query = $this->database->select('users_field_data', 'u')
        ->fields('u', ['uid', 'name'])->condition('mail', $email)->execute()->fetchAll();
        
        
        

        if(count($query) > 0) {
        return false;  
        }
        else {
            return true;
        }
    }
}
