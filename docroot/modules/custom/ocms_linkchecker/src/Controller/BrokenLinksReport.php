<?php

namespace Drupal\ocms_linkchecker\Controller;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Routing\RedirectDestinationInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\ocms_linkchecker\PupLinkcheckerAccessInterface;
use Drupal\ocms_linkchecker\BrokenLinksTableTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Display a table of contents for the presentation
 */
class BrokenLinksReport extends ControllerBase {

  /**
   * Drupal's database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Drupal's translation service.
   *
   * @var \Drupal\Core\StringTranslation\TranslationInterface
   */
  protected $translation;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * OCMS Linkchecker Access Service.
   *
   * @var \Drupal\ocms_linkchecker\PupLinkcheckerAccessInterface
   */
  protected $access;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The redirect destination service.
   *
   * @var \Drupal\Core\Routing\RedirectDestinationInterface
   */
  protected $redirectDestination;

  /**
   * The query ::build will use.
   *
   * @var
   */
  protected $query;

  /**
   * Response codes that should not be considered errors.
   *
   * @var array
   */
  protected $ignoreResponseCodes;

  /**
   * Constructs the broken links controller object.
   *
   * @param \Drupal\Core\Database\Connection
   *   The database connection
   * @param \Drupal\Core\StringTranslation\TranslationInterface
   *   The translation service
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\ocms_linkchecker\PupLinkcheckerAccessInterface
   *   The OCMS Linkchecker Access service
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Drupal\Core\Routing\RedirectDestinationInterface
   */
  public function __construct(Connection $database, TranslationInterface $string_translation, ConfigFactoryInterface $config_factory, PupLinkcheckerAccessInterface $ocms_linkchecker_access, AccountInterface $current_user, RedirectDestinationInterface $redirect_destination) {
    $this->connection = $database;
    $this->translation = $string_translation;
    $this->configFactory = $config_factory;
    $this->access = $ocms_linkchecker_access;
    $this->currentUser = $current_user;
    $this->redirectDestination = $redirect_destination;

    $config = $this->configFactory->get('ocms_linkchecker.settings');
    $this->ignoreResponseCodes = preg_split('/(\r\n?|\n)/',
      $config->get('error.ignore_response_codes'));

    // Search for broken links in entities of all users.
    $subquery = $this->connection->select('ocms_linkchecker_entity', 'le')
      ->distinct()
      ->fields('le', array('lid'));

    // Build pager query.
    $query = $this->connection->select('ocms_linkchecker_link', 'll')
      ->extend('\Drupal\Core\Database\Query\PagerSelectExtender')
      ->extend('\Drupal\Core\Database\Query\TableSortExtender');
    $query->innerJoin($subquery, 'q2', 'q2.lid = ll.lid');
    $query->fields('ll');
    $query->condition('ll.last_checked', 0, '<>');
    $query->condition('ll.status', 1);
    $query->condition('ll.code', $this->ignoreResponseCodes, 'NOT IN');

    $this->query = $query;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('string_translation'),
      $container->get('config.factory'),
      $container->get('ocms_linkchecker.access'),
      $container->get('current_user'),
      $container->get('redirect.destination')
    );
  }

  use BrokenLinksTableTrait;
}
