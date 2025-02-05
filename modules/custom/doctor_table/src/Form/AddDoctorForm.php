<?php

namespace Drupal\doctor_table\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class AddDoctorForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_doctor_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
    ];

    $form['degree'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Degree'),
      '#required' => TRUE,
    ];

    $form['contact_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Contact Number'),
      '#required' => TRUE,
    ];

    $form['address'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Address'),
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $fields = [
      'name' => $form_state->getValue('name'),
      'degree' => $form_state->getValue('degree'),
      'contact_number' => $form_state->getValue('contact_number'),
      'address' => $form_state->getValue('address'),
    ];

    // Insert data into the database table.
    \Drupal::database()->insert('doctor')
      ->fields($fields)
      ->execute();

    $this->messenger()->addMessage($this->t('Doctor information saved successfully.'));
  }
}
