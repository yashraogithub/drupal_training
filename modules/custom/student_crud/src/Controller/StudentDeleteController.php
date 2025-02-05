<?php

namespace Drupal\student_crud\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Handles the deletion of student records.
 */
class StudentDeleteController extends ControllerBase {

  /**
   * Deletes a student record.
   *
   * @param int $id
   *   The ID of the student to delete.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   Redirects to the student list page.
   */
  public function delete($id) {
    // Connect to the database.
    $conn = Database::getConnection();

    // Check if the student exists.
    $record = $conn->select('student_crud', 's')
      ->fields('s', ['id'])
      ->condition('id', $id)
      ->execute()
      ->fetchField();

    if ($record) {
      // Perform the delete operation.
      $conn->delete('student_crud')
        ->condition('id', $id)
        ->execute();

      // Add a success message.
      $this->messenger()->addMessage($this->t('Student record deleted successfully.'));
    }
    else {
      // Add an error message if the record doesn't exist.
      $this->messenger()->addError($this->t('The student record does not exist.'));
    }

    // Redirect to the student list page.
    return new RedirectResponse('/student-crud/list');
  }

}
