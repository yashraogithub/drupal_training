<?php

namespace Drupal\custom_crud\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Provides a controller to delete a customer.
 */
class DeleteCustomerController extends ControllerBase {

  /**
   * Deletes a customer from the database.
   *
   * @param int $id
   *   The ID of the customer to delete.
   */
  public function deleteCustomer($id) {
    $connection = Database::getConnection();
    $customer = $connection->select('custom_crud_customers', 'c')
      ->fields('c', ['id', 'name'])
      ->condition('id', $id)
      ->execute()
      ->fetchAssoc();

    if (!$customer) {
      $this->messenger()->addError($this->t('Customer not found.'));
      return $this->redirect('custom_crud.customer_list');
    }

    // Delete the customer record.
    $connection->delete('custom_crud_customers')
      ->condition('id', $id)
      ->execute();
      
    // Invalidate the cache tag.
    \Drupal::service('cache_tags.invalidator')->invalidateTags(['custom_crud_customer_list']);  

    // Add a success message and redirect to the customer list.
    $this->messenger()->addMessage($this->t('Customer %name has been deleted.', ['%name' => $customer['name']]));
    return $this->redirect('custom_crud.customer_list');
  }
}
