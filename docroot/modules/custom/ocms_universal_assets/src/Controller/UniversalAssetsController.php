<?php

namespace Drupal\ocms_universal_assets\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller routines for page example routes.
 */
class UniversalAssetsController extends ControllerBase {

  /**
   * Universal assets page content.
   *
   * @return array
   *   Markup to display on universal asset page.
   */
  public function content() {
    return [
      '#markup' => '',
    ];
  }

}
