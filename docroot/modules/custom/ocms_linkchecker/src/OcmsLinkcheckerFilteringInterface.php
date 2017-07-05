<?php

namespace Drupal\ocms_linkchecker;

/**
 * Defines an interface for the Link filtering services.
 */
interface PupLinkcheckerFilteringInterface {

  /**
   * Calls the core check_markup function with the "filters to skip" argument.
   *
   * @see: https://api.drupal.org/api/drupal/core%21modules%21filter%21filter.module/function/check_markup/8.3.x
   */
  public function checkMarkup($text, $formatId = NULL, $langcode = '');
}
