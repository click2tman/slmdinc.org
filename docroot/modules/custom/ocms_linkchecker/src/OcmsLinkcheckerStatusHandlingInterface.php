<?php

namespace Drupal\ocms_linkchecker;

/**
 * Defines an interface for the Link status handling services.
 */
interface PupLinkcheckerStatusHandlingInterface {

  /**
   * Reacts to the status code of a checked link.
   *
   * @param object $response
   *   An object containing the HTTP request headers, response code, headers,
   *   data and redirect status.
   * @param string $link
   *   An object containing the url, lid and fail_count.
   */
  public function handleStatus(&$response, $link);
}
