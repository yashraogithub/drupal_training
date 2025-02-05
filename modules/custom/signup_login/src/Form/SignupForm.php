<?php

namespace Drupal\signup_login\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\Component\Utility\Crypt;

/**
 * Provides a signup form.
 */
class SignupForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'signup_form';
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

    $form['surname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Surname'),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];

    $form['password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#required' => TRUE,
    ];

    $form['contact_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Contact Number'),
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Sign Up'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $password = $form_state->getValue('password');

    $hashed_password = \Drupal::service('password')->hash($form_state->getValue('password'));

    Drupal::database()->insert('signup_login_users')
      ->fields([
        'name' => $form_state->getValue('name'),
        'surname' => $form_state->getValue('surname'),
        'email' => $form_state->getValue('email'),
        'password' => $hashed_password,
        'contact_number' => $form_state->getValue('contact_number'),
      ])
      ->execute();

    $this->messenger()->addMessage($this->t('Signup successful!'));
    $form_state->setRedirect('signup_login.login_form');

  }

  
}
