<?php
namespace Drupal\ocms_viewfiles\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Configures taxonomy used for breadcrumb settings for this site.
 */
class PagerForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ocms_viewfiles_form';
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
     $first_image=file_create_url(drupal_get_path('module', 'ocms_viewfiles') . '/css/first.png');
	 $prev_image=file_create_url(drupal_get_path('module', 'ocms_viewfiles') . '/css/prev.png');
	 $next_image=file_create_url(drupal_get_path('module', 'ocms_viewfiles') . '/css/next.png');
	 $last_image=file_create_url(drupal_get_path('module', 'ocms_viewfiles') . '/css/last.png');
     $form['text']['#markup'] ='<img src="'.$first_image.'" class="first"/><img src="'.$prev_image.'" class="prev"/>';
  
	$page_terms=array("25"=>"25","50"=>"50", "100"=>"100");
    
	$form['pagedisplay'] = array(
	  '#required'=> FALSE,
      '#type' => 'textfield',
       '#attributes' => array('class' => array('pagedisplay'), 'readonly' => 'readonly','size' => 6),
    );
    $form['text2']['#markup'] ='<img src="'.$next_image.'" class="next"/><img src="'.$last_image.'" class="last"/>';
  
	$form['amount'] = [
     '#type' => 'select',
     '#attributes' => array('class' => array('pagesize'),'width'=>'4px'),
    '#multiple' => false,
    '#options' => $page_terms,
    '#default_value' => '50',
    ];

    return $form;
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
