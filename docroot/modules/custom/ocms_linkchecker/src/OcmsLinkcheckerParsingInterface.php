<?php

namespace Drupal\ocms_linkchecker;

/**
 * Defines an interface for the Link parsing services.
 */
interface PupLinkcheckerParsingInterface {

  /**
   * Parses the urls from an entity.
   *
   * This function parse all fields from the entity and returns an array of
   * filtered field items.
   *
   * @param string $entityType
   *   The type of entity; e.g., 'node', 'comment'.
   * @param string $bundleName
   *   The name of the bundle aka node type, e.g., 'article', 'page'.
   * @param object $entity
   *   The entity to parse, a $node or a $comment object.
   * @param bool $returnFieldNames
   *   If set to TRUE, the returned array will contain the content as keys, and
   *   each element will be an array containing all field names in which the
   *   content is found. Otherwise, a simple array with content will be returned.
   *
   * @return array
   *   Array of field items with filters applied.
   */
  public function parseFields($entityType, $bundleName, $entity, $returnFieldNames = FALSE);

  /**
   * Gets the path of a URL.
   *
   * @param string $url
   *   The http/https URL to parse.
   *
   * @return string
   *   Full qualified URL with absolute path of the URL.
   */
  public function getAbsoluteUrl($url);
}
