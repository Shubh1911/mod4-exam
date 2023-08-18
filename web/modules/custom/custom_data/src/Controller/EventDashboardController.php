<?php

namespace Drupal\custom_data\Controller;

use Drupal\Core\Controller\ControllerBase;
use Exception;

/**
 * Controller for the Event Dashboard.
 */
class EventDashboardController extends ControllerBase
{

  /**
   * Generates the dashboard page.
   *
   * @return array
   *   A renderable array containing the dashboard content.
   */
  public function dashboard()
  {
    try {
      $db = \Drupal::database();

      // Fetching data related to user entity fields.
      $query = $db->select('user__field_joining_date', 'u');
      $query->addField('u', 'entity_id', 'user_id');
      $query->addField('u', 'field_joining_date_value', 'joining_date');
      // Add more fields if needed.
      $query->condition('u.bundle', 'user');
      // Add more conditions if needed.
      $user_data = $query->execute()->fetchAll();

      // Fetching data related to the 'Passing Year' field.
      $passing_year_query = $db->select('user__field_passing_year', 'p');
      $passing_year_query->addField('p', 'entity_id', 'user_id');
      $passing_year_query->addField('p', 'field_passing_year_value', 'passing_year');
      // Add more fields if needed.
      $passing_year_query->condition('p.bundle', 'user');
      // Add more conditions if needed.
      $passing_year_data = $passing_year_query->execute()->fetchAll();

      // Fetching display name data from the users_field_data table.
      $display_name_query = $db->select('users_field_data', 'ufd');
      $display_name_query->addField('ufd', 'uid', 'user_id');
      $display_name_query->addField('ufd', 'name', 'display_name');
      // Add more fields if needed.
      $display_name_data = $display_name_query->execute()->fetchAll();

      return [
        '#theme' => 'custom_dashboard',
        '#user_data' => $user_data,
        '#passing_year_data' => $passing_year_data,
        '#display_name_data' => $display_name_data,
      ];
    } catch (Exception $e) {
      // Handle exceptions.
      return [
        '#markup' => 'Error: ' . $e->getMessage(),
      ];
    }
  }

}