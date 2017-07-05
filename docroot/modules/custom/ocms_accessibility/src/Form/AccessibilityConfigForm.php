<?php

namespace Drupal\ocms_accessibility\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A configuration form for custom accessibility settings.
 */
class AccessibilityConfigForm extends ConfigFormBase {
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($config_factory);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ocms_accessibility_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('ocms_accessibility.settings');

    $content_type_options = [];
    $content_types = $this->entityTypeManager
      ->getStorage('node_type')
      ->loadMultiple();

    // Content type machine/name.
    foreach ($content_types as $content) {
      $content_type_options[$content->get('type')] = $content->get('name');
    }

    // Content type options.
    $form['content_types'] = [
      '#type' => 'checkboxes',
      '#options' => $content_type_options,
      '#title' => $this->t('Content types to apply accessibility check on submit:'),
      '#default_value' => $config->get('content_types'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable('ocms_accessibility.settings')
      ->set('content_types', $form_state->getValue('content_types'))
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['ocms_accessibility.settings'];
  }

}
