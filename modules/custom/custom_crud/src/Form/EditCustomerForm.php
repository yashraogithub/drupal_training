<?php

namespace Drupal\custom_crud\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Provides a form to edit customer details.
 */
class EditCustomerForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_crud_edit_customer_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $connection = Database::getConnection();
    $record = $connection->select('custom_crud_customers', 'c')
      ->fields('c', ['id', 'name', 'email', 'contact_no', 'address'])
      ->condition('id', $id)
      ->execute()
      ->fetchAssoc();

    if (!$record) {
      $this->messenger()->addError($this->t('Customer not found.'));
      return [];
    }

    // Prepopulate form fields with the record data.
    $form['id'] = [
      '#type' => 'hidden',
      '#value' => $record['id'],
    ];
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#default_value' => $record['name'],
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#default_value' => $record['email'],
      '#required' => TRUE,
    ];
    $form['contact_no'] = [
      '#type' => 'tel',
      '#title' => $this->t('Contact Number'),
      '#default_value' => $record['contact_no'],
      '#required' => TRUE,
    ];
    $form['address'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Address'),
      '#default_value' => $record['address'],
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Update Customer'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $id = $form_state->getValue('id');
    $fields = [
      'name' => $form_state->getValue('name'),
      'email' => $form_state->getValue('email'),
      'contact_no' => $form_state->getValue('contact_no'),
      'address' => $form_state->getValue('address'),
    ];

    $connection = Database::getConnection();
    $connection->update('custom_crud_customers')
      ->fields($fields)
      ->condition('id', $id)
      ->execute();
    // Invalidate the cache tag for the customer list.
      \Drupal::service('cache_tags.invalidator')->invalidateTags(['custom_crud_customer_list']);

    $this->messenger()->addMessage($this->t('Customer details updated successfully.'));
    $form_state->setRedirect('custom_crud.customer_list');
  }

}
