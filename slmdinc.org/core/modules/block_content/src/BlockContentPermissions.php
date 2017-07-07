<?php

namespace Drupal\block_content;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\block_content\Entity\BlockContentType;

/**
 * Provides dynamic permissions for blocks of different types.
 */
class BlockContentPermissions {

  use StringTranslationTrait;

  /**
   * Returns an array of block type permissions.
   *
   * @return array
   *   The block content type permissions.
   *
   * @see \Drupal\user\PermissionHandlerInterface::getPermissions()
   */
  public function blockTypePermissions() {
    $perms = array();
    // Generate block permissions for all block types.
    foreach (BlockContentType::loadMultiple() as $type) {
      $perms += $this->buildPermissions($type);
    }

    return $perms;
  }

  /**
   * Returns a list of block permissions for a given block type.
   *
   * @param \Drupal\block_content\Entity\BlockContentType $type
   *   The block type.
   *
   * @return array
   *   An associative array of permission names and descriptions.
   */
  protected function buildPermissions(BlockContentType $type) {
    $type_id = $type->id();
    $type_params = ['%type_name' => $type->label()];

    return [
      "create $type_id blocks" => [
        'title' => $this->t('%type_name: Create new blocks', $type_params),
      ],
      "edit any $type_id blocks" => [
        'title' => $this->t('%type_name: Edit any blocks', $type_params),
      ],
      "delete any $type_id blocks" => [
        'title' => $this->t('%type_name: Delete any blocks', $type_params),
      ],
    ];
  }

}
