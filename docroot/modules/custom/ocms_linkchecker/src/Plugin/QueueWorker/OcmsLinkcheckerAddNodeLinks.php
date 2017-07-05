<?php

namespace Drupal\ocms_linkchecker\Plugin\QueueWorker;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\ocms_linkchecker\PupLinkcheckerDatabaseInterface;
use Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Updates the aquifer content types.
 *
 * @QueueWorker(
 *   id = "ocms_linkchecker_scan_node",
 *   title = @Translation("Scan a node for links"),
 *   cron = {"time" = 60}
 * )
 */
class PupLinkcheckerAddNodeLinks extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * The Database service
   * @var Drupal\ocms_linkchecker\PupLinkcheckerDatabaseInterface
   */
  protected $database;

  /**
   * OCMS Linkchecker Utility Service.
   *
   * @var \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface
   */
  protected $utilityService;

  /**
   * Constructs the Queue Worker
   *
   * @param \Drupal\ocms_linkchecker\PupLinkcheckerDatabaseInterface $database
   *   The OCMS Linkchecker Database Service.
   * @param \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface $ocms_linkchecker_utility
   *   The OCMS Linkchecker Utility Service.
   */
  public function __construct(PupLinkcheckerDatabaseInterface $database, PupLinkcheckerUtilityInterface $ocms_linkchecker_utility) {
    $this->database = $database;
    $this->utilityService = $ocms_linkchecker_utility;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('ocms_linkchecker.database'),
      $container->get('ocms_linkchecker.utility')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    $node = $this->utilityService->loadEntity('node', $data);
    if (is_object($node)) {
      $this->database->addNodeLinks($node);
    }
  }

}
