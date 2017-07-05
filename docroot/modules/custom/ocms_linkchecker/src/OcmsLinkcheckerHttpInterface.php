<?php

namespace Drupal\ocms_linkchecker;

/**
 * Defines an interface for the Link http services.
 */
interface PupLinkcheckerHttpInterface {

  /**
   * Checks if a link is broken.
   *
   * @param object $link
   *   The fully loaded link to check.
   */
  public function checkLink($link);
}
