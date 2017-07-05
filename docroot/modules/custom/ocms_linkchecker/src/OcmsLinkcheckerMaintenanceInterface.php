<?php

namespace Drupal\ocms_linkchecker;

/**
 * Defines an interface for the Link maintenance services.
 */
interface PupLinkcheckerMaintenanceInterface {

  /**
   * Deletes all links without a reference.
   *
   * This is triggered via cron.
   *
   * For speed reasons and check results we keep the links for some time
   * as they may be reused by other new content.
   */
  public function deleteUnreferencedLinks();

  /**
   * Deletes links to node types that are no longer scanned.
   *
   * This is triggered via cron.
   */
  public function deleteUnscannedNodeLinks();

  /**
   * Checks links and processes the response code appropriately.
   *
   * This is triggered via cron.
   */
  public function checkLinks();

  /**
   * Scans all nodes of a given node type for links.
   *
   * This is triggered by a node type being set to scan for links.
   *
   * @param string $nodeType
   *   The machine name for the node
   */
  public function scanNodeType($nodeType);

  /**
   * Removes all link references for nodes of a given type.
   *
   * This is triggered by a node type being set to not scan for links.
   *
   * @param string $nodeType
   *   The machine name for the node
   */
  public function removeNodeType($nodeType);
}
