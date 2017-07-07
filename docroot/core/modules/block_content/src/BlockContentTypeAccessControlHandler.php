<?php

namespace Drupal\block_content;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the block type.
 *
 * @see \Drupal\block_content\Entity\BlockContentType
 */
class BlockContentTypeAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    if ($account->hasPermission('administer blocks')) {
      return AccessResult::allowed()->cachePerPermissions();
    }

    if ($operation == 'view') {
      return AccessResult::allowed();
    }

    return parent::checkAccess($entity, $operation, $account);
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIf($account->hasPermission('administer blocks') || $account->hasPermission('administer block types'))->cachePerPermissions();
  }

}
