<?php

namespace Drupal\student_crud\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\student_crud\Service\EmailValidationService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;



/**
 * Provides a student registration form.
 */
class RegistrationForm extends FormBase {

  protected $emailValidationService;

  protected $database;

  /**
   * Constructs the form.
   */
  public function __construct(
  EmailValidationService $emailValidationService,
  Connection $database
  
  ) {
    $this->emailValidationService = $emailValidationService;
    $this->database = $database;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('email_validator.service'),
      $container->get('database'),

    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'student_registration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    

    $form['student_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Student ID'),
      '#required' => TRUE,
    ];
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
    ];
    $form['course'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Course'),
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];
    $form['contact'] = [  
      '#type' => 'tel',
      '#title' => $this->t('Contact Number'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
    ];



    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $email_validator = \Drupal::service('email_validator.service');
    if (!$email_validator->isCustomValidEmail($email)) {
      $form_state->setErrorByName('email', $this->t('Now static drupal call  service call email address is not valid.'));
    }
    if (!$email_validator->checkIfUserExist($email)) {
      $form_state->setErrorByName('email', $this->t('Email already exist please choose another.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $conn = Database::getConnection();
    $conn->insert('student_crud')->fields([
      'student_id' => $form_state->getValue('student_id'),
      'name' => $form_state->getValue('name'),
      'course' => $form_state->getValue('course'),
      'email' => $form_state->getValue('email'),
      'contact' => $form_state->getValue('contact'),
    ])->execute();

    $this->messenger()->addMessage($this->t('Student registered successfully.'));
    $form_state->setRedirect('student_crud.list');
  }

}
