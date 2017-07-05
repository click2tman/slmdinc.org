<?php

namespace Drupal\ocms_linkchecker;

/**
 * Defines an interface for the Link replacement services.
 */
interface PupLinkcheckerReplacementInterface {

  /**
   * Replaces the old url by a new url on 301 status codes.
   *
   * @param string $entityType
   *   The type of entity; e.g., 'node', 'comment'.
   * @param string $bundleName
   *   The name of the bundle aka node type, e.g., 'article', 'page'.
   * @param object $entity
   *   The entity to parse, a $node or a $comment object.
   * @param string $oldUrl
   *   The previous url.
   * @param string $newUrl
   *   The new url to replace the old.
   *
   * @return object
   */
  public function replaceFields($entityType, $bundleName, $entity, $oldUrl, $newUrl);

  /**
   * Replaces an old link with a new link in text.
   *
   * @param string $text
   *   The text a link is inside. Passed in as a reference.
   * @param string $oldLink
   *   The old link to search for in strings.
   * @param string $newLink
   *   The old link should be overwritten with this new link.
   */
  public function replaceLink(&$text, $oldLink, $newLink);
}
