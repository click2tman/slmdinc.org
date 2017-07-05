<?php

namespace Drupal\ocms_media_access;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\media_entity\Entity\MediaBundle;

/**
 * Provides granular permissions for media entity types.
 */
class MediaAccessPermissions {
  use StringTranslationTrait;

  /**
   * Returns an array of media type permissions.
   *
   * @return array
   *   The media type permissions.
   *
   * @see \Drupal\user\PermissionHandlerInterface::getPermissions()
   */
  public function mediaTypePermissions() {
    $perms = array();
    // Generate permissions for all media types.
    foreach (MediaBundle::loadMultiple() as $type) {
      $perms += $this->buildPermissions($type);
    }

    return $perms;
  }

  /**
   * Returns a list of media permissions for a given media type.
   *
   * @param \Drupal\media_entity\Entity\MediaBundle $type
   *   The media type.
   *
   * @return array
   *   An associative array of permission names and descriptions.
   */
  protected function buildPermissions(MediaBundle $type) {
    $type_id = $type->id();
    $type_params = ['%type_name' => $type->label()];

    return [
      "create $type_id media" => [
        'title' => $this->t('%type_name: Create new media', $type_params),
      ],
      "edit any $type_id media" => [
        'title' => $this->t('%type_name: Edit any media', $type_params),
      ],
      "edit own $type_id media" => [
        'title' => $this->t('%type_name: Edit own media', $type_params),
      ],
      "delete any $type_id media" => [
        'title' => $this->t('%type_name: Delete any media', $type_params),
      ],
      "delete own $type_id media" => [
        'title' => $this->t('%type_name: Delete own media', $type_params),
      ],
    ];
  }
}
