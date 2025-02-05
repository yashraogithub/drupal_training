<?php

namespace Drupal\student_crud\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides a form for editing a student record.
 */
class StudentEditForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'student_edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    // Load the existing student record.
    $conn = Database::getConnection();
    $record = $conn->select('student_crud', 's')
      ->fields('s', ['id', 'student_id', 'name', 'course', 'email', 'contact'])
      ->condition('id', $id)
      ->execute()
      ->fetchAssoc();

    if (!$record) {
      $this->messenger()->addError($this->t('Student record not found.'));
      return new RedirectResponse('/student-crud/list');
    }

    // Store the student ID in the form state for use in the submit handler.
    $form_state->set('student_id', $id);

    // Build the form fields with existing values.
    $form['student_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Student ID'),
      '#default_value' => $record['student_id'],
      '#required' => TRUE,
    ];
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#default_value' => $record['name'],
      '#required' => TRUE,
    ];
    $form['course'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Course'),
      '#default_value' => $record['course'],
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#default_value' => $record['email'],
      '#required' => TRUE,
    ];
    $form['contact'] = [
      '#type' => 'tel',
      '#title' => $this->t('Contact Number'),
      '#default_value' => $record['contact'],
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Update'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    if (!\Drupal::service('email.validator')->isValid($email)) {
      $form_state->setErrorByName('email', $this->t('The email address is not valid.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $conn = Database::getConnection();
    $conn->update('student_crud')
      ->fields([
        'student_id' => $form_state->getValue('student_id'),
        'name' => $form_state->getValue('name'),
        'course' => $form_state->getValue('course'),
        'email' => $form_state->getValue('email'),
        'contact' => $form_state->getValue('contact'),
      ])
      ->condition('id', $form_state->get('student_id'))
      ->execute();

    $this->messenger()->addMessage($this->t('Student record updated successfully.'));
    $form_state->setRedirect('student_crud.list');
  }

}
