<?php

namespace Drupal\admin_config\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

class ConfigForm extends FormBase {
  
  public function getFormId() {
    return 'config_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

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

    $form['country'] = array(
      '#type' => 'textfield',
      '#title' => t('Country:'),
      '#required' => TRUE,
      '#default_value' => $country_data,
    );

    $form['city'] = array(
      '#type' => 'textfield',
      '#title' => t('City:'),
      '#required' => TRUE,
      '#default_value' => $city_data,
    );

    $form['timezone'] = array(
      '#type' => 'select',
      '#title' => t('Timezone:'),
      '#options' => array('America/Chicago' => 'America/Chicago', 'America/New_York' => 'America/New_York', 'Asia/Tokyo' => 'Asia/Tokyo', 'Asia/Dubai' => 'Asia/Dubai', 'Asia/Kolkata' => 'Asia/Kolkata', 'Europe/Amsterdam' => 'Europe/Amsterdam', 'Europe/Oslo' => 'Europe/Oslo', 'Europe/London' => 'Europe/London'),
      '#required' => TRUE,
      '#default_value' => $timezone,
    );

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#default_value' => $this->t('Save') ,
    ];

    return $form;

  }

   public function submitForm(array &$form, FormStateInterface $form_state) {
      $country = !empty($form_state->getValue('country')) ? $form_state->getValue('country') : '';
      $city = !empty($form_state->getValue('city')) ? $form_state->getValue('city') : '';
      $timezone = !empty($form_state->getValue('timezone')) ? $form_state->getValue('timezone') : '';

      $database = \Drupal::database();

      $select_qry = $database->select('timezone_data', 'td');
      $select_qry->fields('td',['country','city','timezone']);
      $select_data_rec = $select_qry->execute()->fetchAll();

      if($select_data_rec){
        $database->update('timezone_data')
            ->fields(array(
                'country' => $country,
                'city' => $city,       
                'timezone' => $timezone,       
             ))->execute();
      }else{

        $database->insert('timezone_data')
            ->fields(array(
                'country' => $country,
                'city' => $city,       
                'timezone' => $timezone,       
             ))->execute();
      }

      // drupal_set_message("successfully saved Security Settings"); 
    
   }
}