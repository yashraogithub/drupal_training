<?php

namespace Drupal\custom_crud\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
// use Drupal\Core\Database\Database;
use Drupal\Core\Database\Connection;
use Drupal\custom_crud\Service\EmailValidator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form to add customers.
 */
class CustomerForm extends FormBase {

  /**
   * The email validator service.
   *
   * @var \Drupal\custom_crud\Service\EmailValidator
   */
  protected $emailValidator;

   /**
   * The database connection service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a new EmailValidationForm.
   *
   * @param \Drupal\custom_crud\Service\EmailValidator $emailValidator
   *   The email validator service.
   */
  public function __construct(EmailValidator $emailValidator, Connection $database) {
    $this->emailValidator = $emailValidator;
    $this->database = $database;
  }

 /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('custom_crud.email_validator'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_crud_customer_form';
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
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];
    $form['contact_no'] = [
      '#type' => 'tel',
      '#title' => $this->t('Contact Number'),
      '#required' => TRUE,
    ];
    $form['address'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Address'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Customer'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $contact_no = $form_state->getValue('contact_no');

    // Validate the email address.
    if (!$this->emailValidator->isValidEmail($email)) {
      $form_state->setErrorByName('email', $this->t('The email address %email is invalid. Please enter a valid email.', ['%email' => $email]));
    }

      // Check for duplicate email in the database.
      $existing_email = $this->database->select('custom_crud_customers', 'c')
      ->fields('c', ['id'])
      ->condition('email', $email)
      ->execute()
      ->fetchField();
  
    if ($existing_email) {
      $form_state->setErrorByName('email', $this->t('A customer with this email already exists.'));
    }
  
    // Check for duplicate contact number in the database.
    $existing_contact = $this->database->select('custom_crud_customers', 'c')
      ->fields('c', ['id'])
      ->condition('contact_no', $contact_no)
      ->execute()
      ->fetchField();
  
    if ($existing_contact) {
      $form_state->setErrorByName('contact_no', $this->t('A customer with this contact number already exists.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
   
    $fields = [
      'name' => $form_state->getValue('name'),
      'email' => $form_state->getValue('email'),
      'contact_no' => $form_state->getValue('contact_no'),
      'address' => $form_state->getValue('address'),
    ];
    //  $database = \Drupal::service('database');
    // $connection = Database::getConnection();
    $this->database->insert('custom_crud_customers')->fields($fields)->execute();

    // Invalidate the cache tag for the customer list.
    \Drupal::service('cache_tags.invalidator')->invalidateTags(['custom_crud_customer_list']);

    $this->messenger()->addMessage($this->t('Customer added successfully!'));
    $form_state->setRedirect('custom_crud.customer_list');
  }
}
