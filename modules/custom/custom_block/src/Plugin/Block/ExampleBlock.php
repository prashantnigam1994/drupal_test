<?php

namespace Drupal\custom_block\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

 /**
 * Provides a block with a simple text.
 *
 * @block(
 *   id = "example_block",
 *   admin_label = @Translation("Example Block"),
 * )
 */

class ExampleBlock extends BlockBase {
  
  /**
   * {@block}
   */

  public function build() {

    $database = \Drupal::database();

    $select_qry1 = $database->select('timezone_data', 'td');
    $select_qry1->fields('td',['country']);
    $select_data_rec1 = $select_qry1->execute()->fetchAll();
    $country_data = $select_data_rec1[0]->country;

    $select_qry2 = $database->select('timezone_data', 'td');
    $select_qry2->fields('td',['city']);
    $select_data_rec2 = $select_qry2->execute()->fetchAll();
    $city_data = $select_data_rec2[0]->city;

    $select_qry3 = $database->select('timezone_data', 'td');
    $select_qry3->fields('td',['timezone']);
    $select_data_rec3 = $select_qry3->execute()->fetchAll();
    $timezone = $select_data_rec3[0]->timezone;

    // $data = \Drupal::service('test_function.custom_services')->getData();

    $date_formatter = \Drupal::service('date.formatter');

    $date_time = new DrupalDateTime('', new \DateTimeZone('UTC'));
    $timestamp = $date_time->getTimestamp();

    $type = 'custom_date';
    
    $format = '';
    $langcode = NULL;

    $formatted = $date_formatter->format($timestamp, $type, $format, $timezone, $langcode);

    return [
      '#markup' => 'Location: '.$country_data.", ".$city_data.'<br>Current Time: '.$formatted, 
    ];
  }

  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  public function getCacheMaxAge() {
    return 0;
  }

} 