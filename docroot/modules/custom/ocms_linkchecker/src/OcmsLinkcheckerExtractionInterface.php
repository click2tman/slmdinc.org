<?php

namespace Drupal\ocms_linkchecker;

/**
 * Defines an interface for the Link extraction services.
 */
interface PupLinkcheckerExtractionInterface {

  /**
   * Extracts links from content.
   *
   * @param string $text
   *   The text to be scanned for links.
   * @param string $contentPath
   *   Path to the content that is currently scanned for links. This value is
   *   required to build full qualified links from relative links. Relative links
   *   are not extracted from content, if path is not provided.
   *
   * @return array
   *   Array whose keys are fully qualified and unique URLs found in the
   *   content, and whose values are arrays of actual text (raw URLs or paths)
   *   corresponding to each fully qualified URL.
   */
  public function extractContentLinks($text, $contentPath);

  /**
   * Extracts links from a node.
   *
   * @param object $node
   *   The fully populated node object.
   * @param boolean $returnFieldNames
   *   If set to TRUE, the returned array will contain the link URLs as keys, and
   *   each element will be an array containing all field names in which the URL
   *   is found. Otherwise, a simple array of URLs will be returned.
   *
   * @return array
   *   An array whose keys are fully qualified and unique URLs found in the node
   *   (as returned by extractContentLinks()), or a more complex
   *   structured array (see above) if $returnFieldNames is TRUE.
   */
  public function extractNodeLinks($node, $returnFieldNames = FALSE);

  /**
   * Extracts links from a comment.
   *
   * @param object $comment
   *   The fully populated comment object.
   * @param boolean $returnFieldNames
   *   If set to TRUE, the returned array will contain the link URLs as keys, and
   *   each element will be an array containing all field names in which the URL
   *   is found. Otherwise, a simple array of URLs will be returned.
   *
   * @return array
   *   An array whose keys are fully qualified and unique URLs found in the node
   *   (as returned by extractContentLinks()), or a more complex
   *   structured array (see above) if $returnFieldNames is TRUE.
   */
  public function extractCommentLinks($comment, $returnFieldNames = FALSE);

  /**
   * Extracts links from a block.
   *
   * @param object $block
   *   The fully populated block object.
   * @param boolean $returnFieldNames
   *   If set to TRUE, the returned array will contain the link URLs as keys, and
   *   each element will be an array containing all field names in which the URL
   *   is found. Otherwise, a simple array of URLs will be returned.
   *
   * @return array
   *   An array whose keys are fully qualified and unique URLs found in the node
   *   (as returned by extractContentLinks()), or a more complex
   *   structured array (see above) if $returnFieldNames is TRUE.
   */
  public function extractBlockLinks($block, $returnFieldNames = FALSE);
}
