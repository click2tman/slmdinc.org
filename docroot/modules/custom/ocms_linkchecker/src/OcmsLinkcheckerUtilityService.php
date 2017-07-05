<?php

namespace Drupal\ocms_linkchecker;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\State\StateInterface;

/**
 * Implements the PupLinkcheckeryUtilityInterface.
 */
class PupLinkcheckerUtilityService implements PupLinkcheckerUtilityInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Drupal's database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Drupal's entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs the OCMS Linkchecker Utility Service object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\State\StateInterface
   *   The state service
   * @param \Drupal\Core\Database\Connection
   *   The database connection
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface
   *   The entity type manager service
   */
  public function __construct(ConfigFactoryInterface $config_factory, StateInterface $state, Connection $database, EntityTypeManagerInterface $entity_type_manager) {
    $this->configFactory = $config_factory;
    $this->state = $state;
    $this->connection = $database;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function isValidResponseCode($responseCode) {
  }

  /**
   * {@inheritdoc}
   */
  public function isDefaultrevision($entity) {
  }

  /**
   * {@inheritdoc}
   */
  public function recurseArrayValues(array $array) {
    $arrayValues = array();

    foreach ($array as $value) {
      if (is_array($value)) {
        $arrayValues = array_merge($arrayValues, $this->recurseArrayValues($value));
      }
      else {
        $arrayValues[] = $value;
      }
    }

    return $arrayValues;
  }

  /**
   * {@inheritdoc}
   */
  public function shouldCheckLink($url) {
    $config = $this->configFactory->get('ocms_linkchecker.settings');
    $status = TRUE;

    // Is url in domain blacklist?
    $excludedUrls = $config->get('check.disable_for_urls');
    if (!empty($excludedUrls)) {
      $excludeLinks = preg_split('/(\r\n?|\n)/', $excludedUrls);
      $escapedExcludedLinks = array();
      foreach ($excludeLinks as $excludeLink) {
        $escapedExcludedLinks[] = preg_quote($excludeLink, '/');
      }
      $pattern = implode('|', $escapedExcludedLinks);
      if (preg_match('/' . $pattern . '/', $url)) {
        $status = FALSE;
      }
    }

    // Protocol whitelist check (without curl, only http/https is supported).
    if (!preg_match('/^(https?):\/\//i', $url)) {
      $status = FALSE;
    }

    return $status;
  }

  /**
   * {@inheritdoc}
   */
  public function isInternalUrl(&$link) {
  }

  /**
   * {@inheritdoc}
   */
  public function getBlock($bid) {
  }

  /**
   * {@inheritdoc}
   */
  public function scanNodeTypes() {
    $types = array();
    foreach (node_type_get_names() as $type => $name) {
      $scanNode = $this->state->get('ocms_linkchecker.scan_node_' . $type) ?: 0;
      if ($scanNode) {
        $types[$type] = $type;
      }
    }
    return $types;
  }

  /**
   * {@inheritdoc}
   */
  public function scanCommentTypes() {
  }

  /**
   * {@inheritdoc}
   */
  public function impersonateUser($newUser = NULL) {
  }

  /**
   * {@inheritdoc}
   */
  public function revertUser() {
  }

  /**
   * {@inheritdoc}
   */
  public function loadLink($lid) {
    return $this->connection->query('SELECT *
      FROM {ocms_linkchecker_link}
      WHERE lid = :lid',
      array(':lid' => $lid)
    )->fetchObject();
  }

  /**
   * {@inheritdoc}
   */
  public function updateLink($link) {
    $this->connection->update('ocms_linkchecker_link')
      ->fields(array(
        'method' => $link->method,
	'fail_count' => $link->fail_count,
	'last_checked' => $link->last_checked,
	'status' => $link->status
      ))
      ->condition('lid', $link->lid)
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function loadEntity($entityType, $id) {
    return $this->entityTypeManager->getStorage($entityType)->load($id);
  }

}
