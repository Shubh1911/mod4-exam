<?php

/**
 * @file
 * A form to collect an email address for RSVP details.
 */

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Vocabulary;

class CustomUserDetails extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'custom_user_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['full_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Full Name'),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email Address'),
      '#required' => TRUE,
    ];

    $form['password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#required' => TRUE,
    ];

    $form['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone Number'),
      '#required' => TRUE,
    ];

    $form['stream'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'taxonomy_term',
      '#title' => $this->t('Stream'),
      '#required' => TRUE,
      '#selection_settings' => [
        'target_bundles' => ['branch'],
      ],
    ];

    $form['joining_year'] = [
      '#type' => 'number',
      '#title' => $this->t('Joining Year'),
      '#required' => TRUE,
    ];

    $form['passing_year'] = [
      '#type' => 'number',
      '#title' => $this->t('Passing Year'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // Create a new user using the submitted form data.
    $user = \Drupal\user\Entity\User::create();

    // Set basic user information.
    $user->setUsername($form_state->getValue('full_name'));
    $user->setEmail($form_state->getValue('email'));
    $user->setPassword($form_state->getValue('password'));

    // Set additional user fields.
    $user->set('field_phone_number', $form_state->getValue('phone_number'));
    $user->set('field_stre', $form_state->getValue('stream'));
    $user->set('field_joining_date', $form_state->getValue('joining_year'));
    $user->set('field_passing_year', $form_state->getValue('passing_year'));

    // Assign roles.
    $user->addRole('student'); // Default authenticated role.
    // Add more roles as needed.

    // Save the user.
    $user->enforceIsNew(); // Force the user to be treated as new.
    $user->save();

    // Display a success message.
    \Drupal::messenger()->addMessage($this->t('User registration successful. You can now log in.'));

    // Redirect to the user login page.
    $form_state->setRedirect('user.login');
  }

}