<?php

namespace Drupal\ocms_linkchecker;

/**
 * Defines an interface for the Link database services.
 */
interface PupLinkcheckerDatabaseInterface {

  /**
   * Adds node links to database.
   *
   * @param object $node
   *   The fully populated node object.
   * @param bool $skipMissingLinksDetection
   *   To prevent endless batch loops the value needs to be TRUE. With FALSE
   *   the need for content re-scans is detected by the number of missing links.
   */
  public function addNodeLinks($node, $skipMissingLinksDetection = FALSE);

  /**
   * Adds comment links to database.
   *
   * @param object $comment
   *   The fully populated comment object.
   * @param bool $skipMissingLinksDetection
   *   To prevent endless batch loops the value needs to be TRUE. With FALSE
   *   the need for content re-scans is detected by the number of missing links.
   */
  public function addCommentLinks($comment, $skipMissingLinksDetection = FALSE);

  /**
   * Adds block links to database.
   *
   * @param array|object $block
   *   The fully populated block object.
   * @param int $bid
   *   Block id from table {block}.bid.
   * @param bool $skipMissingLinksDetection
   *   To prevent endless batch loops the value needs to be TRUE. With FALSE
   *   the need for content re-scans is detected by the number of missing links.
   */
  public function addBlockLinks($block, $bid, $skipMissingLinksDetection = FALSE);

  /**
   * Removes all node references to links in the {ocms_linkchecker_entity} table.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node object.
   */
  public function deleteNodeLinks(\Drupal\node\NodeInterface $node);

  /**
   * Sets all references to a node as broken.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node object.
   */
  public function setBrokenNodeReferences(\Drupal\node\NodeInterface $node);

  /**
   * Removes all comment references to links in the {ocms_linkchecker_entity}
   * table.
   *
   * @param object $comment
   *   The comment object.
   */
  public function deleteCommentLinks($comment);

  /**
   * Removes all block references to links in the {ocms_linkchecker_entity} table.
   *
   * @param object $block
   *   The block object.
   */
  public function deleteBlockLinks($block);

  /**
   * Deletes expired node references to links in the {ocms_linkchecker_entity}
   * table.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node object.
   * @param array $links
   */
  public function deleteExpiredNodeReferences(\Drupal\node\NodeInterface $node, $links = array());

  /**
   * Deletes expired comment references to links in the
   * {ocms_linkchecker_entity} table.
   *
   * @param object $comment
   *   The comment object.
   * @param array $links
   */
  public function deleteExpiredCommentReferences($comment, $links = array());

  /**
   * Deletes expired block references to links in the {ocms_linkchecker_entity}
   * table.
   *
   * @param object $block
   *   The block object.
   * @param array $links
   */
  public function deleteBlockReferences($block, $links = array());

  /**
   * Returns an array of node references missing in the {ocms_linkchecker_entity}
   * table.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node object.
   * @param array $links
   *   An array of links.
   *
   * @return array
   *   An array of node references missing in the {ocms_linkchecker_entity} table.
   */
  public function getMissingNodeReferences(\Drupal\node\NodeInterface $node, $links);

  /**
   * Returns an array of comment references missing in the
   * {ocms_linkchecker_entity} table.
   *
   * @param object $comment
   *   The comment object.
   * @param array $links
   *   An array of links.
   *
   * @return array
   *   An array of comment references missing in the {ocms_linkchecker_entity}
   *   table.
   */
  public function getMissingCommentReferences($comment, $links);

  /**
   * Returns an array of block references missing in the {ocms_linkchecker_entity}
   * table.
   *
   * @param object $block
   *   The block object.
   * @param array $links
   *   An array of links.
   *
   * @return array
   *   An array of block references missing in the {ocms_linkchecker_entity}
   *   table.
   */
  public function getMissingBlockReferences($block, $links);

  /**
   * Unpublishes all nodes having the specified link id.
   *
   * @param int $lid
   *   A link ID that have reached a defined failcount.
   */
  public function unpublishNodes($lid);
}
