<?php

namespace Drupal\ocms_linkchecker;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Queue\QueueFactory;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Implements the PupLinkcheckerMaintenanceInterface.
 */
class PupLinkcheckerMaintenanceService implements PupLinkcheckerMaintenanceInterface {

  use StringTranslationTrait;

  /**
   * OCMS Linkchecker Utility Service.
   *
   * @var \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface
   */
  protected $utilityService;

  /**
   * Drupal's database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Drupal's queue service.
   *
   * @var \Drupal\Core\Queue\QueueFactory
   */
  protected $queueFactory;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs the OCMS Linkchecker Maintenance Service object.
   *
   * @param \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface
   *   The OCMS Linkchecker Utility Service.
   * @param \Drupal\Core\Database\Connection
   *   The database connection
   * @param \Drupal\Core\Queue\QueueInterface
   *   The Queue service
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation.
   */
  public function __construct(PupLinkcheckerUtilityInterface $ocms_linkchecker_utility, Connection $database, QueueFactory $queue_factory, ConfigFactoryInterface $config_factory, TranslationInterface $string_translation) {
    $this->utilityService = $ocms_linkchecker_utility;
    $this->connection = $database;
    $this->queueFactory = $queue_factory;
    $this->configFactory = $config_factory;
    $this->stringTranslation = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public function deleteUnreferencedLinks() {
    $linkchecker_entity = $this->connection->select('ocms_linkchecker_entity', 'le')
      ->distinct()
      ->fields('le', array('lid'));

    $this->connection->delete('ocms_linkchecker_link')
      ->condition('lid', $linkchecker_entity, 'NOT IN')
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function deleteUnscannedNodeLinks() {
    $nodeTypes = $this->utilityService->scanNodeTypes();
    if (!empty($nodeTypes)) {
      $subquery1 = $this->connection->select('node', 'n')
        ->fields('n', array('nid'))
        ->condition('n.type', $nodeTypes, 'NOT IN');

      $this->connection->delete('ocms_linkchecker_entity')
        ->condition('entity_id', $subquery1, 'IN')
        ->condition('entity_type', 'node')
        ->execute();

      // @todo: Remove comments link references from table.
    }
    else {
      // No active node types. Remove all items from table.
      $this->connection->delete('ocms_linkchecker_entity')
        ->condition('entity_type', 'node')
        ->execute();
      // @todo Remove comments link references from table.
    }
  }

  /**
   * {@inheritdoc}
   */
  public function checkLinks() {
    $config = $this->configFactory->get('ocms_linkchecker.settings');
    $links = $this->connection->query('SELECT lid
      FROM {ocms_linkchecker_link}
      WHERE last_checked < :nextCheck
      AND status = :checkLink
      ORDER BY last_checked, lid ASC
    ', array(
      ':nextCheck' => REQUEST_TIME - $config->get('check.interval'),
      ':checkLink' => 1
    ));
    $this->queueFactory->get('ocms_linkchecker_check_links')->createQueue();
    $queue = $this->queueFactory->get('ocms_linkchecker_check_links');
    foreach ($links as $link) {
      // We will always add the item to the queue. The QueueWorker will double
      // check whether the item is being processed again too soon on account of
      // an older item that had not made it through a previous cron run. It
      // could be too expensive to check all the items in the queue to see if
      // we should skip adding this.
      $queue->createItem($link->lid);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function scanNodeType($nodeType) {
    $this->queueFactory->get('ocms_linkchecker_scan_node')->createQueue();
    $queue = $this->queueFactory->get('ocms_linkchecker_scan_node');
    $results = $this->connection->select('node', 'n')
      ->fields('n', array('nid'))
      ->condition('n.type', $nodeType)
      ->execute();
    foreach ($results as $result) {
      $queue->createItem($result->nid);
    }
    \Drupal::logger('ocms_linkchecker')
      ->notice($this->t('All nodes of type: :nodeType will be scanned for links.', array(':nodeType' => $nodeType)));
  }

  /**
   * {@inheritdoc}
   */
  public function removeNodeType($nodeType) {
    $subquery1 = $this->connection->select('node', 'n')
      ->fields('n', array('nid'))
      ->condition('n.type', $nodeType);

    $this->connection->delete('ocms_linkchecker_entity')
      ->condition('entity_id', $subquery1, 'IN')
      ->condition('entity_type', 'node')
      ->execute();
    \Drupal::logger('ocms_linkchecker')
      ->notice($this->t('Removed link references for node type: :nodeType.', array(':nodeType' => $nodeType)));
  }

}
