<?php

namespace Drupal\custom_crud\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Provides a list of customers.
 */
class CustomerListController extends ControllerBase {

  /**
   * Display customers.
   */
  public function customerList() {
    // Add Customer link
    $add_customer_url = Url::fromRoute('custom_crud.add_customer');
    $add_customer_link = [
      '#type' => 'link',
      '#title' => $this->t('Add Customer'),
      '#url' => $add_customer_url,
      '#attributes' => [
        'class' => ['add-customer-link'],
      ],
    ];

    // Customer table headers
    $header = [
      'id' => $this->t('ID'),
      'name' => $this->t('Name'),
      'email' => $this->t('Email'),
      'contact_no' => $this->t('Contact Number'),
      'address' => $this->t('Address'),
      'actions' => $this->t('Actions'),
      'Delete' => $this->t('Delete'),
    ];

    $rows = [];
    $connection = Database::getConnection();
    $query = $connection->select('custom_crud_customers', 'c')
      ->fields('c', ['id', 'name', 'email', 'contact_no', 'address'])
      ->execute();

    foreach ($query as $record) {
    // Generate Edit link
      $edit_url = Url::fromRoute('custom_crud.edit_customer', ['id' => $record->id]);
      $edit_link = [
        '#type' => 'link',
        '#title' => $this->t('Edit'),
        '#url' => $edit_url,
        '#attributes' => ['class' => ['edit-link']],
      ];

    // Generate Delete link
        $delete_url = Url::fromRoute('custom_crud.delete_customer', ['id' => $record->id]);
        $delete_link = [
           '#type' => 'link',
           '#title' => $this->t('Delete'),
           '#url' => $delete_url,
           '#attributes' => ['class' => ['delete-link']],
        ];

      $rows[] = [
        'data' => [
          $record->id,
          $record->name,
          $record->email,
          $record->contact_no,
          $record->address,
        //   \Drupal::service('renderer')->render($edit_link) . ' | ' . \Drupal::service('renderer')->render($delete_link), // Render Edit and Delete links
          \Drupal::service('renderer')->render($edit_link), // Render Edit link
          \Drupal::service('renderer')->render($delete_link),
        ],
      ];
    }

    // Render the Add Customer link and customer table together
    return [
      '#attached' => [
        'library' => [
          'custom_crud/crud_menu', // Attach the custom CSS library
        ],
      ],

      '#cache' => [
        'tags' => ['custom_crud_customer_list'], // Associate the cache tag
        ],

      'add_customer_link' => $add_customer_link,
      'customer_table' => [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#empty' => $this->t('No customers found.'),
        '#attributes' => [
            'class' => ['table'], // Add table class for custom styling
          ],
      ],
    ];
  }
}
