<?php

namespace Drupal\student_crud\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Handles student data operations.
 */
class StudentController extends ControllerBase {

  /**
   * Displays the list of students.
   *
   * @return array
   *   A renderable array containing the table.
   */
  public function listStudents() {

     // Create Register Button.
     $register_button = [
      '#type' => 'link',
      '#title' => $this->t('Register'),
      '#url' => Url::fromRoute('student_crud.register'),
      '#attributes' => [
          'class' => ['button', 'button--success'],
      ],
  ];
    // Table header.
    $header = [
      ['data' => $this->t('ID')],
      ['data' => $this->t('Student ID')],
      ['data' => $this->t('Name')],
      ['data' => $this->t('Course')],
      ['data' => $this->t('Email')],
      ['data' => $this->t('Contact')],
      ['data' => $this->t('Operations')],
      ['data' => $this->t('Operations')],
    ];

    // Fetch data from the database.
    $conn = Database::getConnection();
    $query = $conn->select('student_crud', 's')
      ->fields('s', ['id', 'student_id', 'name', 'course', 'email', 'contact'])
      ->execute();
    $results = $query->fetchAll();

    // Prepare rows for the table.
    $rows = [];
    foreach ($results as $data) {
      // Create the "Edit" button.
      $edit_button = [
        'data' => [
          '#type' => 'link',
          '#title' => $this->t('Edit'),
          '#url' => Url::fromRoute('student_crud.edit', ['id' => $data->id]),
          '#attributes' => [
            'class' => ['button', 'button--primary'],
          ],
        ],
      ];

      // Create the "Delete" button.
      $delete_button = [
        'data' => [
          '#type' => 'link',
          '#title' => $this->t('Delete'),
          '#url' => Url::fromRoute('student_crud.delete', ['id' => $data->id]),
          '#attributes' => [
            'class' => ['button', 'button--danger'],
            'onclick' => "return confirm('Are you sure you want to delete this student?');", // JavaScript confirmation.
          ],
        ],
      ];
      // Add the row to the table with Edit and Delete buttons.
      $rows[] = [
        'data' => [
          $data->id,
          $data->student_id,
          $data->name,
          $data->course,
          $data->email,
          $data->contact,
          $edit_button,
          $delete_button,
        ],
      ];
      
    }

    // Render the table andf attach the CSS library.
    return [
      '#type' => 'container',
      'register' => $register_button,
      'table' => [
          '#theme' => 'table',
          '#header' => $header,
          '#rows' => $rows,
          '#empty' => $this->t('No students found.'),
      ],
      '#attached' => [
          'library' => ['student_crud/global-styles'],
      ],
      '#cache' => [
        'max-age' => 0, // Completely disables caching
    ],
  ];
  }

}
