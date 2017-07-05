<?php
namespace Drupal\ocms_taxonomy_breadcrumb\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Configures taxonomy used for breadcrumb settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ocms_taxonomy_breadcrumb_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'ocms_taxonomy_breadcrumb.configuration',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ocms_taxonomy_breadcrumb.configuration');
    $taxonomy_terms=array("navigation"=>"Navigation", "channels"=>"Channels");
    $form['ocms_taxonomy_breadcrumb'] = [
     '#type' => 'select',
    '#title' => t('Click on your city'),
    '#multiple' => false,
    '#options' => $taxonomy_terms,
    '#title' => $this->t('Taxonomy Term to use for Breadcrumbs'),
      '#description' => $this->t('Choose the Taxonomy Term to use for Breadcrumbs for this site.'),
      '#default_value' => $config->get('breadcrumb_taxonomy'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    //dsm ("The value on submit is ". $values['ocms_taxonomy_breadcrumb']);
    $this->config('ocms_taxonomy_breadcrumb.configuration')
      ->set('breadcrumb_taxonomy', $values['ocms_taxonomy_breadcrumb'])
      ->save();
    parent::submitForm($form, $form_state);
  }
}
