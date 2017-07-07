<?php

namespace Drupal\block_content;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the custom block entity type.
 *
 * @see \Drupal\block_content\Entity\BlockContent
 */
class BlockContentAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $block_content, $operation, AccountInterface $account) {
    $type = $block_content->bundle();

    switch ($operation) {
      case 'view':
        return AccessResult::allowed();

      case 'create':
        return AccessResult::allowedIfHasPermission($account, 'create ' . $type . ' blocks');

      case 'update':
        if ($account->hasPermission('edit any ' . $type . ' blocks', $account)) {
          return AccessResult::allowed()->cachePerPermissions();
        }

      case 'delete':
        if ($account->hasPermission('delete any ' . $type . ' blocks', $account)) {
          return AccessResult::allowed()->cachePerPermissions();
        }
    }

    return parent::checkAccess($block_content, $operation, $account);
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIf($account->hasPermission('administer blocks') || $account->hasPermission('create ' . $entity_bundle . ' blocks'))->cachePerPermissions();
  }

}
