<?php

namespace Drupal\ocms_linkchecker;

/**
 * Defines an interface for the Link utility services.
 */
interface PupLinkcheckerUtilityInterface {

  /**
   * Defines the list of allowed response codes for form input validation.
   *
   * @param int $code
   *   A numeric response code.
   *
   * @return boolean
   *   TRUE if the status code is valid, otherwise FALSE.
   */
  public function isValidResponseCode($responseCode);

  /**
   * Checks if this entity is the default revision (published).
   *
   * @param object $entity
   *   The entity object, e.g., $node.
   *
   * @return bool
   *   TRUE if the entity is the default revision, FALSE otherwise.
   */
  public function isDefaultrevision($entity);

  /**
   * Returns all the values of one-dimensional and multidimensional arrays.
   *
   * @return array
   *   Returns all the values from the input array and indexes the array numerically.
   */
  public function recurseArrayValues(array $array);

  /**
   * Determines if the link status should be checked or not.
   *
   * This checks against reserved URLS, user-selected excluded URLs and
   * the protocol.
   *
   * @return boolean
   *   Whether to be checked
   */
  public function shouldCheckLink($url);

  /**
   * Checks if the link is an internal URL or not.
   *
   * @param object $link
   *   Link object.
   *
   * @return bool
   *   TRUE if link is internal, otherwise FALSE.
   */
  public function isInternalUrl(&$link);

  /**
   * Returns information from database about a block.
   *
   * @param int $bid
   *   ID of the block to get information for.
   *
   * @return object
   *   Associative object of information stored in the database for this block.
   *   Object keys:
   *   - module: 'block' as the source of the custom blocks data.
   *   - delta: Block ID.
   *   - info: Block description.
   *   - body['value']: Block contents.
   *   - body['format']: Filter ID of the filter format for the body.
   */
  public function getBlock($bid);

  /**
   * Returns all content type enabled with link checking.
   *
   * @return array
   *   An array of node type names, keyed by the type.
   */
  public function scanNodeTypes();

  /**
   * Returns all content type enabled with comment link checking.
   *
   * @return array
   *   An array of node type names, keyed by the type.
   */
  public function scanCommentTypes();

  /**
   * Impersonates another user, see http://drupal.org/node/287292#comment-3162350.
   *
   * Each time this function is called, the active user is saved and $new_user
   * becomes the active user. Multiple calls to this function can be nested,
   * and session saving will be disabled until all impersonation attempts have
   * been reverted using linkchecker_revert_user().
   *
   * @param int|object $new_user
   *   User to impersonate, either a UID or a user object.
   *
   * @return object
   *   Current user object.
   *
   * @see linkchecker_revert_user()
   */
  public function impersonateUser($newUser = NULL);

  /**
   * Reverts to the previous user after impersonating.
   *
   * @return object
   *   Current user.
   *
   * @see linkchecker_impersonate_user()
   */
  public function revertUser();

  /**
   * Load a link object
   *
   * @param int $lid
   *   The link id
   *
   * @return object $link
   *  The fully loaded link object
   */
  public function loadLink($lid);

  /**
   * Update a link.
   *
   * The user sets the values on the Edit link settings form.
   *
   * @param object $link
   *   The fully loaded link object
   */
  public function updateLink($link);

  /**
   * Load an entity object
   *
   * @param string $entityType
   *   The entity type
   * @param int $id
   *   The entity id
   *
   * @return object $entity
   *  The fully loaded entity object
   */
  public function loadEntity($entityType, $id);
}
