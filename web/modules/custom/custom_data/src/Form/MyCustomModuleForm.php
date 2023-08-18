<?php

namespace Drupal\custom_data\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * My custom module form.
 */
class MyCustomModuleForm extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'my_custom_module_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['user_id'] = [
            '#type' => 'textfield',
            '#title' => $this->t('User ID'),
            '#required' => TRUE,
        ];
        $form['new_joining_year'] = [
            '#type' => 'date',
            '#title' => $this->t('New Joining Year'),
            '#required' => TRUE,
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Update Joining Year'),
        ];
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $user_id = $form_state->getValue('user_id');
        $new_joining_year = $form_state->getValue('new_joining_year');

        // Update the joining year in the 'user__field_joining_date' table.
        $update_query = \Drupal::database()->update('user__field_joining_date')
            ->fields(['field_joining_date_value' => $new_joining_year])
            ->condition('entity_id', $user_id)
            ->execute();

        if ($update_query) {
            \Drupal::messenger()->addMessage($this->t('Joining year updated successfully.'));
        } else {
            \Drupal::messenger()->addError($this->t('Failed to update joining year.'));
        }
    }

}