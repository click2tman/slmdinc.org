<?php

namespace Drupal\ocms_linkchecker;

use Drupal\Core\Database\Connection;

/**
 * Implements the PupLinkcheckerAccessInterface.
 */
class PupLinkcheckerAccessService implements PupLinkcheckerAccessInterface {

  /**
   * Drupal's database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * OCMS Linkchecker Utility Service.
   *
   * @var \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface
   */
  protected $utilityService;

  /**
   * Constructs the OCMS Linkchecker Access Service object.
   *
   * @param \Drupal\Core\Database\Connection
   *   The database connection
   * @param \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface $ocms_linkchecker_utility
   *   The OCMS Linkchecker Utility Service.
   */
  public function __construct(Connection $database, PupLinkcheckerUtilityInterface $ocms_linkchecker_utility) {
    $this->connection = $database;
    $this->utilityService = $ocms_linkchecker_utility;
  }

  /**
   * {@inheritdoc}
   */
  public function accessBlockIds($link) {
  }

  /**
   * {@inheritdoc}
   */
  public function accessCommentIds($link, $commentAuthorAccount = NULL) {
  }

  /**
   * {@inheritdoc}
   */
  public function accessLink($link) {
  }

  /**
   * {@inheritdoc}
   */
  public function accessNodeIds($link, $nodeAuthorAccount = NULL) {
    static $fieldsWithNodeLinks = array();

    // Exit if all node types are disabled or if the user cannot access content.
    $linkcheckerScanNodeTypes = $this->utilityService->scanNodeTypes();
//    if (empty($linkchecker_scan_nodetypes) || !user_access('access content')) {
    if (empty($linkcheckerScanNodeTypes)) {
      return array();
    }

    // @todo: Port over the rest of the logic that actually checks for access
    //        This is just a cherry-picked snippet of the code I'm supposed to
    //        have.
    $query = $this->connection->select('node', 'n');
    $query->innerJoin('ocms_linkchecker_entity', 'le', 'le.entity_id = n.nid');
    $query->condition('le.lid', $link->lid);
    $query->condition('le.entity_type', 'node');
    $query->fields('n', array('nid'));

    $nodes = $query->execute();
    foreach ($nodes as $node) {
      $nids[] = $node->nid;
    }
    return $nids;
  }
}
