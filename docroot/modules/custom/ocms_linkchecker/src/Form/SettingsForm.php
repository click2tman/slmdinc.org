<?php

namespace Drupal\ocms_linkchecker\Form;

use Drupal\Component\Plugin\FallbackPluginManagerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\RfcLogLevel;
use Drupal\Core\Url;
use Drupal\filter\FilterPluginCollection;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configures link checker settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The plugin manager filter service.
   *
   * @var \Drupal\Component\Plugin\FallbackPluginManagerInterface
   */
  protected $pluginManagerFilter;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Drupal's database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a \Drupal\ocms_linkchecker\SettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Component\Plugin\FallbackPluginManagerInterface $plugin_manager_filter
   *   The plugin manager filter
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter
   * @param \Drupal\Core\Database\Connection
   *   The database connection
   */
  public function __construct(ConfigFactoryInterface $config_factory, ModuleHandlerInterface $module_handler, FallbackPluginManagerInterface $plugin_manager_filter, DateFormatterInterface $date_formatter, Connection $database) {
    parent::__construct($config_factory);
    $this->moduleHandler = $module_handler;
    $this->pluginManagerFilter = $plugin_manager_filter;
    $this->dateFormatter = $date_formatter;
    $this->connection = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('module_handler'),
      $container->get('plugin.manager.filter'),
      $container->get('date.formatter'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ocms_linkchecker_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['ocms_linkchecker.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ocms_linkchecker.settings');

    $form['general'] = [
      '#type' => 'details',
      '#title' => $this->t('General settings'),
      '#description' => $this->t('Configure the <a href=":url">content types</a> that should be scanned for broken links.', [':url' => Url::fromRoute('entity.node_type.collection')->toString()]),
      '#open' => TRUE,
    ];

    $block_custom_dependencies = '<div class="admin-requirements">';
    $block_custom_dependencies .= $this->t('Requires: @module-list', [
      '@module-list' => $this->moduleHandler->moduleExists('block') ?
        $this->t('@module (<span class="admin-enabled">enabled</span>)', [
          '@module' => 'Block'
        ]) :
        $this->t('@module (<span class="admin-disabled">disabled</span>)', [
          '@module' => 'Block'
        ]),
    ]);
    $block_custom_dependencies .= '</div>';

    $form['general']['ocms_linkchecker_scan_blocks'] = [
      '#default_value' => $config->get('general.scan_blocks'),
      '#type' => 'checkbox',
      '#title' => $this->t('Scan blocks for links'),
      '#description' => $this->t('Enable this checkbox if links in blocks should be checked.') . $block_custom_dependencies,
      '#disabled' => !$this->moduleHandler->moduleExists('block'),
    ];
    $form['general']['ocms_linkchecker_check_links_types'] = [
      '#type' => 'select',
      '#title' => $this->t('What type of links should be checked?'),
      '#description' => $this->t('A fully qualified link (http://example.com/foo/bar) to a page is considered external, whereas an absolute (/foo/bar) or relative link (node/123) without a domain is considered internal.'),
      '#default_value' => $config->get('general.check_link_types'),
      '#options' => [
        '0' => $this->t('Internal and external'),
        '1' => $this->t('External only (http://example.com/foo/bar)'),
        '2' => $this->t('Internal only (node/123)'),
      ],
    ];

    $form['extract'] = [
      '#type' => 'details',
      '#title' => $this->t('Link extraction'),
      '#open' => TRUE,
    ];
    $form['extract']['ocms_linkchecker_extract_from_a'] = [
      '#default_value' => $config->get('extract.from_a'),
      '#type' => 'checkbox',
      '#title' => $this->t('Extract links in <code>&lt;a&gt;</code> and <code>&lt;area&gt;</code> tags'),
      '#description' => $this->t('Enable this checkbox if normal hyperlinks should be extracted. The anchor element defines a hyperlink, the named target destination for a hyperlink, or both. The area element defines a hot-spot region on an image, and associates it with a hypertext link.'),
    ];
    $form['extract']['ocms_linkchecker_extract_from_audio'] = [
      '#default_value' => $config->get('extract.from_audio'),
      '#type' => 'checkbox',
      '#title' => $this->t('Extract links in <code>&lt;audio&gt;</code> tags including their <code>&lt;source&gt;</code> and <code>&lt;track&gt;</code> tags'),
      '#description' => $this->t('Enable this checkbox if links in audio tags should be extracted. The audio element is used to embed audio content.'),
    ];
    $form['extract']['ocms_linkchecker_extract_from_embed'] = [
      '#default_value' => $config->get('extract.from_embed'),
      '#type' => 'checkbox',
      '#title' => $this->t('Extract links in <code>&lt;embed&gt;</code> tags'),
      '#description' => $this->t('Enable this checkbox if links in embed tags should be extracted. This is an obsolete and non-standard element that was used for embedding plugins in past and should no longer used in modern websites.'),
    ];
    $form['extract']['ocms_linkchecker_extract_from_iframe'] = [
      '#default_value' => $config->get('extract.from_iframe'),
      '#type' => 'checkbox',
      '#title' => $this->t('Extract links in <code>&lt;iframe&gt;</code> tags'),
      '#description' => $this->t('Enable this checkbox if links in iframe tags should be extracted. The iframe element is used to embed another HTML page into a page.'),
    ];
    $form['extract']['ocms_linkchecker_extract_from_img'] = [
      '#default_value' => $config->get('extract.from_img'),
      '#type' => 'checkbox',
      '#title' => $this->t('Extract links in <code>&lt;img&gt;</code> tags'),
      '#description' => $this->t('Enable this checkbox if links in image tags should be extracted. The img element is used to add images to the content.'),
    ];
    $form['extract']['ocms_linkchecker_extract_from_object'] = [
      '#default_value' => $config->get('extract.from_object'),
      '#type' => 'checkbox',
      '#title' => $this->t('Extract links in <code>&lt;object&gt;</code> and <code>&lt;param&gt;</code> tags'),
      '#description' => $this->t('Enable this checkbox if multimedia and other links in object and their param tags should be extracted. The object tag is used for flash, java, quicktime and other applets.'),
    ];
    $form['extract']['ocms_linkchecker_extract_from_video'] = [
      '#default_value' => $config->get('extract.from_video'),
      '#type' => 'checkbox',
      '#title' => $this->t('Extract links in <code>&lt;video&gt;</code> tags including their <code>&lt;source&gt;</code> and <code>&lt;track&gt;</code> tags'),
      '#description' => $this->t('Enable this checkbox if links in video tags should be extracted. The video element is used to embed video content.'),
    ];

    // Get all filters available on the system.
    $bag = new FilterPluginCollection($this->pluginManagerFilter, []);
    $filter_info = $bag->getAll();
    $filter_options = [];
    $filter_descriptions = [];

    foreach ($filter_info as $name => $filter) {
      if (in_array($name, $config->get('extract.filter.default_blacklist'))) {
        $filter_options[$name] = $this->t('@title <span class="marker">(Recommended)</span>', array('@title' => $filter->getLabel()));
      }
      else {
        $filter_options[$name] = $filter->getLabel();
      }
      $filter_descriptions[$name] = [
        '#description' => $filter->getDescription(),
      ];
    }

    $form['extract']['ocms_linkchecker_filter_blacklist'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Text formats disabled for link extraction'),
      '#default_value' => $config->get('extract.filter.blacklist'),
      '#options' => $filter_options,
      '#description' => $this->t('<strong>NOTE: Do NOT check any of these boxes until <a href="https://www.drupal.org/node/2876287">https://www.drupal.org/node/2876287</a> has been fixed.</strong> If a filter has been enabled for an input format it runs first and afterwards the link extraction. This helps the link checker module to find all links normally created by custom filters (e.g. Markdown filter, Bbcode). All filters used as inline references (e.g. Weblink filter <code>[link: id]</code>) to other content and filters only wasting processing time (e.g. Line break converter) should be disabled. This setting does not have any effect on how content is shown on a page. This feature optimizes the internal link extraction process for link checker and prevents false alarms about broken links in content not having the real data of a link.'),
    ];
    $form['extract']['ocms_linkchecker_filter_blacklist'] = array_merge($form['extract']['ocms_linkchecker_filter_blacklist'], $filter_descriptions);

    // @todo: Am I forced to use db_query against my own table? Or is there
    //        something akin to nodeStorage (entity storage)?
    $countLidsEnabled = $this->connection->query("SELECT count(lid) FROM {ocms_linkchecker_link} WHERE status = :status", array(':status' => 1))->fetchField();
    $countLidsDisabled = $this->connection->query("SELECT count(lid) FROM {ocms_linkchecker_link} WHERE status = :status", array(':status' => 0))->fetchField();
    $form['check'] = [
      '#type' => 'details',
      '#title' => $this->t('Check settings'),
      '#description' => $this->t('Currently the site has @count links (@count_enabled enabled / @count_disabled disabled).', ['@count' => $countLidsEnabled + $countLidsDisabled, '@count_enabled' => $countLidsEnabled, '@count_disabled' => $countLidsDisabled]),
      '#open' => TRUE,
    ];
    $form['check']['ocms_linkchecker_check_connections_max'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of simultaneous connections'),
      '#description' => $this->t('Defines the maximum number of simultaneous connections that can be opened by the server. How do we get Guzzle to make sure that a single domain is not overloaded beyond RFC limits? For small hosting plans with very limited CPU and RAM it may be required to reduce the default limit.'),
      '#default_value' => $config->get('check.max_connections'),
      '#options' => array_combine([2, 4, 8, 16, 24, 32, 48, 64, 96, 128], [2, 4, 8, 16, 24, 32, 48, 64, 96, 128]),
    ];
    $form['check']['ocms_linkchecker_check_useragent'] = [
      '#type' => 'select',
      '#title' => $this->t('User-Agent'),
      '#description' => $this->t('Defines the user agent that will be used for checking links on remote sites. If someone blocks the standard Drupal user agent you can try with a more common browser.'),
      '#default_value' => $config->get('check.user_agent'),
      '#options' => [
        'Drupal (+http://drupal.org/)' => 'Drupal (+http://drupal.org/)',
        'Mozilla/5.0 (Windows NT 6.3; WOW64; Trident/7.0; Touch; rv:11.0) like Gecko' => 'Windows 8.1 (x64), Internet Explorer 11.0',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586' => 'Windows 10 (x64), Edge',
        'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0' => 'Windows 8.1 (x64), Mozilla Firefox 47.0',
        'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0' => 'Windows 10 (x64), Mozilla Firefox 47.0',
      ],
    ];
    $intervals = [86400, 172800, 259200, 604800, 1209600, 2419200, 4838400];
    $period = array_map([$this->dateFormatter, 'formatInterval'], array_combine($intervals, $intervals));
    $form['check']['ocms_linkchecker_check_interval'] = [
      '#type' => 'select',
      '#title' => $this->t('Check interval for links'),
      '#description' => $this->t('This interval setting defines how often cron will re-check the status of links.'),
      '#default_value' => $config->get('check.interval'),
      '#options' => $period,
    ];
    $form['check']['ocms_linkchecker_disable_link_check_for_urls'] = [
      '#default_value' => $config->get('check.disable_for_urls'),
      '#type' => 'textarea',
      '#title' => $this->t('Do not check the link status of links containing these URLs'),
      '#description' => $this->t('By default this list contains the domain names reserved for use in documentation and not available for registration. See <a href=":rfc-2606">RFC 2606</a>, Section 3 for more information. URLs on this list are still extracted, but the link setting <em>Check link status</em> becomes automatically disabled to prevent false alarms. If you change this list you need to clear all link data and re-analyze your content. Otherwise this setting will only affect new links added after the configuration change.', array(':rfc-2606' => 'http://www.rfc-editor.org/rfc/rfc2606.txt')),
    ];
    $form['check']['ocms_linkchecker_logging_level'] = [
      '#default_value' => $config->get('log_level'),
      '#type' => 'select',
      '#title' => $this->t('Log level'),
      '#description' => $this->t('Controls the severity of logging.'),
      '#options' => [
        RfcLogLevel::DEBUG => $this->t('Debug messages'),
        RfcLogLevel::INFO => $this->t('All messages (default)'),
        RfcLogLevel::NOTICE => $this->t('Notices and errors'),
        RfcLogLevel::WARNING => $this->t('Warnings and errors'),
        RfcLogLevel::ERROR => $this->t('Errors only'),
      ],
    ];

    $form['error'] = [
      '#type' => 'details',
      '#title' => $this->t('Error handling'),
      '#description' => $this->t('Defines error handling and custom actions to be executed if specific HTTP requests are failing.'),
      '#open' => TRUE,
    ];
    $ocms_linkchecker_default_impersonate_account = User::load(1);
    $form['error']['ocms_linkchecker_impersonate_account'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Impersonate user account'),
      '#description' => $this->t('If below error handling actions are executed they can be impersonated with a custom user account. By default this is user %name, but you are able to assign a custom user to allow easier identification of these automatic revision updates. Make sure you select a user with <em>full</em> permissions on your site or the user may not able to access and save all content.', array('%name' => $ocms_linkchecker_default_impersonate_account->getAccountName())),
      '#size' => 30,
      '#maxlength' => 60,
      '#autocomplete_path' => 'user/autocomplete',
      '#default_value' => $config->get('error.impersonate_account'),
    ];
    $form['error']['ocms_linkchecker_action_status_code_301'] = [
      '#title' => $this->t('Update permanently moved links'),
      '#description' => $this->t('If enabled, outdated links in content providing a status <em>Moved Permanently</em> (status code 301) are automatically updated to the most recent URL. If used, it is recommended to use a value of <em>three</em> to make sure this is not only a temporarily change. This feature trust sites to provide a valid permanent redirect. A new content revision is automatically created on link updates if <em>create new revision</em> is enabled in the <a href=":content_types">content types</a> publishing options. It is recommended to create new revisions for all link checker enabled content types. Link updates are logged according to your choice of logging system.', array(':content_types' => Url::fromRoute('entity.node_type.collection')->toString())),
      '#type' => 'select',
      '#default_value' => $config->get('error.status_301_action_threshold'),
      '#options' => [
        0 => $this->t('Disabled'),
        1 => $this->t('After one failed check'),
        2 => $this->t('After two failed checks'),
        3 => $this->t('After three failed checks'),
        5 => $this->t('After five failed checks'),
        10 => $this->t('After ten failed checks'),
      ],
    ];
    $form['error']['ocms_linkchecker_action_status_code_404'] = [
      '#title' => $this->t('Unpublish content on file not found error'),
      '#description' => $this->t('If enabled, content with one or more broken links (status code 404) will be unpublished and moved to moderation queue for review after the number of specified checks failed. If used, it is recommended to use a value of <em>three</em> to make sure this is not only a temporarily error.'),
      '#type' => 'select',
      '#default_value' => $config->get('error.status_404_action_threshold'),
      '#options' => [
        0 => $this->t('Disabled'),
        1 => $this->t('After one file not found error'),
        2 => $this->t('After two file not found errors'),
        3 => $this->t('After three file not found errors'),
        5 => $this->t('After five file not found errors'),
        10 => $this->t('After ten file not found errors'),
      ],
    ];
    $form['error']['ocms_linkchecker_ignore_response_codes'] = [
      '#default_value' => $config->get('error.ignore_response_codes'),
      '#type' => 'textarea',
      '#title' => $this->t("Don't treat these response codes as errors"),
      '#description' => $this->t('One HTTP status code per line, e.g. 403.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    $config = $this->config('ocms_linkchecker.settings');

    $form_state->setValue('ocms_linkchecker_disable_link_check_for_urls', trim($form_state->getValue('ocms_linkchecker_disable_link_check_for_urls')));
    $form_state->setValue('ocms_linkchecker_ignore_response_codes', trim($form_state->getValue('ocms_linkchecker_ignore_response_codes')));
    $ignoreResponseCodes = preg_split('/(\r\n?|\n)/', $form_state->getValue('ocms_linkchecker_ignore_response_codes'));
    foreach ($ignoreResponseCodes as $ignoreResponseCode) {
      // @todo: Switch this to use the PuplinkcheckerUtilityService
      if (!$this->validResponseCode($ignoreResponseCode)) {
        $form_state->setErrorByName('ocms_linkchecker_ignore_response_codes', $this->t('Invalid response code %code found.', ['%code' => $ignoreResponseCode]));
      }
    }

    // Prevent the removal of RFC documentation domains. These are the official
    // and reserved documentation domains and not "example" hostnames!
    $ocms_linkchecker_disable_link_check_for_urls = array_filter(preg_split('/(\r\n?|\n)/', $form_state->getValue('ocms_linkchecker_disable_link_check_for_urls')));
    $form_state->setValue('ocms_linkchecker_disable_link_check_for_urls', implode("\n", array_unique(array_merge(explode("\n", trim($config->get('check.reserved_urls'))), $ocms_linkchecker_disable_link_check_for_urls))));

    // Validate impersonation user.
    $ocms_linkchecker_impersonate_account = User::load($form_state->getValue('ocms_linkchecker_impersonate_account'));
    if (!$ocms_linkchecker_impersonate_account) {
      $form_state->setErrorByName('ocms_linkchecker_impersonate_account', $this->t('User account %id cannot be found.', ['%id' => $form_state->getValue('ocms_linkchecker_impersonate_account')]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('ocms_linkchecker.settings')
      ->set('general.scan_blocks', $form_state->getValue('ocms_linkchecker_scan_blocks'))
      ->set('general.check_link_types', $form_state->getValue('ocms_linkchecker_check_links_types'))
      ->set('extract.from_a', $form_state->getValue('ocms_linkchecker_extract_from_a'))
      ->set('extract.from_audio', $form_state->getValue('ocms_linkchecker_extract_from_audio'))
      ->set('extract.from_embed', $form_state->getValue('ocms_linkchecker_extract_from_embed'))
      ->set('extract.from_iframe', $form_state->getValue('ocms_linkchecker_extract_from_iframe'))
      ->set('extract.from_img', $form_state->getValue('ocms_linkchecker_extract_from_img'))
      ->set('extract.from_object', $form_state->getValue('ocms_linkchecker_extract_from_object'))
      ->set('extract.from_video', $form_state->getValue('ocms_linkchecker_extract_from_video'))
      ->set('extract.filter.blacklist', $form_state->getValue('ocms_linkchecker_filter_blacklist'))
      ->set('check.max_connections', $form_state->getValue('ocms_linkchecker_check_connections_max'))
      ->set('check.user_agent', $form_state->getValue('ocms_linkchecker_check_useragent'))
      ->set('check.interval', $form_state->getValue('ocms_linkchecker_check_interval'))
      ->set('check.disable_for_urls', $form_state->getValue('ocms_linkchecker_disable_link_check_for_urls'))
      ->set('check.log_level', $form_state->getValue('ocms_linkchecker_logging_level'))
      ->set('error.impersonate_account', $form_state->getValue('ocms_linkchecker_impersonate_account'))
      ->set('error.status_301_action_threshold', $form_state->getValue('ocms_linkchecker_action_status_code_301'))
      ->set('error.status_404_action_threshold', $form_state->getValue('ocms_linkchecker_action_status_code_404'))
      ->set('error.ignore_response_codes', $form_state->getValue('ocms_linkchecker_ignore_response_codes'))
      ->save();

    // If block scanning has been selected.
//    if ($form_state->getValue('ocms_linkchecker_scan_blocks') > $form['general']['ocms_linkchecker_scan_blocks']['#default_value']) {
//      module_load_include('inc', 'linkchecker', 'linkchecker.batch');
//      batch_set(_linkchecker_batch_import_block_custom());
//    }

    parent::submitForm($form, $form_state);
  }

  /**
   * Defines the list of allowed response codes for form input validation.
   *
   * @param int $code
   *   A numeric response code.
   *
   * @return bool
   *   TRUE if the status code is valid, otherwise FALSE.
   */
  private function validResponseCode($responseCode) {

    $responses = array(
      100 => 'Continue',
      101 => 'Switching Protocols',
      200 => 'OK',
      201 => 'Created',
      202 => 'Accepted',
      203 => 'Non-Authoritative Information',
      204 => 'No Content',
      205 => 'Reset Content',
      206 => 'Partial Content',
      300 => 'Multiple Choices',
      301 => 'Moved Permanently',
      302 => 'Found',
      303 => 'See Other',
      304 => 'Not Modified',
      305 => 'Use Proxy',
      307 => 'Temporary Redirect',
      400 => 'Bad Request',
      401 => 'Unauthorized',
      402 => 'Payment Required',
      403 => 'Forbidden',
      404 => 'Not Found',
      405 => 'Method Not Allowed',
      406 => 'Not Acceptable',
      407 => 'Proxy Authentication Required',
      408 => 'Request Time-out',
      409 => 'Conflict',
      410 => 'Gone',
      411 => 'Length Required',
      412 => 'Precondition Failed',
      413 => 'Request Entity Too Large',
      414 => 'Request-URI Too Large',
      415 => 'Unsupported Media Type',
      416 => 'Requested range not satisfiable',
      417 => 'Expectation Failed',
      500 => 'Internal Server Error',
      501 => 'Not Implemented',
      502 => 'Bad Gateway',
      503 => 'Service Unavailable',
      504 => 'Gateway Time-out',
      505 => 'HTTP Version not supported',
    );

    return array_key_exists($responseCode, $responses);
  }

}
