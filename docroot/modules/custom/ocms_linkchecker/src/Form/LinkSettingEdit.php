<?php

namespace Drupal\ocms_linkchecker\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LinkSettingEdit extends FormBase {

  /**
   * OCMS Linkchecker Utility Service.
   *
   * @var \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface
   */
  protected $utilityService;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs a form object.
   *
   * @param \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface $ocms_linkchecker_utility
   *   The OCMS Linkchecker Utility Service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter
   */
  public function __construct(PupLinkcheckerUtilityInterface $ocms_linkchecker_utility, ConfigFactoryInterface $config_factory, DateFormatterInterface $date_formatter) {
    $this->utilityService = $ocms_linkchecker_utility;
    $this->configFactory = $config_factory;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('ocms_linkchecker.utility'),
      $container->get('config.factory'),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ocms_linkchecker_link_setting_edit';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $linkId = NULL) {

  $link = $this->utilityService->loadLink($linkId);
  $config = $this->configFactory->get('ocms_linkchecker.settings');

  $form['settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Settings'),
    '#collapsible' => FALSE
  );

  $form['settings']['link'] = array('#type' => 'value', '#value' => $link);

  $form['settings']['message'] = array(
    '#type' => 'item',
    '#markup' => $this->t('The link <a href="@url">@url</a> was last checked on @lastChecked and failed @failCount times.', array('@url' => $link->url, '@failCount' => $link->fail_count, '@lastChecked' => $this->dateFormatter->format($link->last_checked)))
  );

  $form['settings']['method'] = array(
    '#type' => 'select',
    '#title' => $this->t('Select request method'),
    '#default_value' => $link->method,
    '#options' => array(
      'HEAD' => $this->t('HEAD'),
      'GET' => $this->t('GET'),
    ),
    '#description' => $this->t('Select the request method used for link checks of this link. If you encounter issues like status code 500 errors with the HEAD request method you should try the GET request method before ignoring a link.'),
  );

  $form['settings']['status'] = array(
    '#default_value' => $link->status,
    '#type' => 'checkbox',
    '#title' => $this->t('Check link status'),
    '#description' => $this->t("Uncheck if you wish to ignore this link. Use this setting only as a last resort if there is no other way to solve a failed link check."),
  );

  $form['maintenance'] = array(
    '#type' => 'fieldset',
    '#title' => t('Maintenance'),
    '#collapsible' => FALSE,
  );

  $form['maintenance']['recheck'] = array(
    '#default_value' => 0,
    '#type' => 'checkbox',
    '#title' => $this->t('Re-check link status on next cron run'),
    '#description' => $this->t('Enable this checkbox if you want to re-check the link during the next cron job rather than wait for the next scheduled check on @date.', array('@date' => $this->dateFormatter->format($link->last_checked + $config->get('check.interval')))),
  );

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save Configuration'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $link = $values['link'];

    // Force link re-check asap.
    if ($values['recheck']) {
      $link->last_checked = 0;
      drupal_set_message($this->t('The link %url will be checked again on the next cron run.', array('%url' => $link->url)));
    }

    if ($values['method'] != $link->method) {
      // Update settings and reset statistics for a quick re-check.
      $link->method = $values['method'];
      $link->fail_count = 0;
      $link->last_checked = 0;
      $link->status = $values['status'];
      drupal_set_message(t('The link settings for %url have been saved and the fail counter has been reset.', array('%url' => $link->url)));
    }
    else {
      // Update setting only.
      $link->method = $values['method'];
      $link->status = $values['status'];
      drupal_set_message(t('The link settings for %url have been saved.', array('%url' => $link->url)));
    }

    $this->utilityService->updateLink($link);
  }

}
