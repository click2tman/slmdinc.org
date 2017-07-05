<?php

namespace Drupal\ocms_linkchecker;

/**
 * Defines an interface for the Link access services.
 */
interface PupLinkcheckerAccessInterface {

  /**
   * Returns block Ids with a link the current user is allowed to view.
   *
   * @param object $link
   *   An object representing the link to check.
   *
   * @return array
   *   An array of block IDs that contain the provided link and that the
   *   current user is allowed to view.
   */
  public function accessBlockIds($link);

  /**
   * Returns comment Ids with a link the current user is allowed to view.
   *
   * @param object $link
   *   An object representing the link to check.
   * @param object $commentAuthorAccount
   *   (optional) If a user account object is provided, the returned comments
   *   will additionally be restricted to only those owned by this account.
   *   Otherwise, comments owned by any user account may be returned.
   *
   * @return array
   *   An array of comment IDs that contain the provided link and that the
   *   current user is allowed to view.
   */
  public function accessCommentIds($link, $commentAuthorAccount = NULL);

  /**
   * Determines if the current user has access to view a link.
   *
   * Link URLs can contain private information (for example, usernames and
   * passwords). So this module should only display links to a user if the link
   * already appears in at least one place on the site where the user would
   * otherwise have access to see it.
   *
   * @param object $link
   *   An object representing the link to check.
   *
   * @return array
   */
  public function accessLink($link);

  /**
   * Returns node Ids with a link the current user may be allowed to view.
   *
   * Important note: For performance reasons, this function is not always
   * guaranteed to return the exact list of node IDs that the current user is
   * allowed to view. It will, however, always return an empty array if the user
   * does not have access to view *any* such nodes, thereby meeting the security
   * goals of _linkchecker_link_access() and other places that call it.
   *
   * In the case where a user has access to some of the nodes that contain the
   * link, this function may return some node IDs that the user does not have
   * access to. Therefore, use caution with its results.
   *
   * @param object $link
   *   An object representing the link to check.
   * @param object $nodeAuthorAccount
   *   (optional) If a user account object is provided, the returned nodes will
   *   additionally be restricted to only those owned by this account.
   *   Otherwise, nodes owned by any user account may be returned.
   *
   * @return array
   *   An array of node IDs that contain the provided link and that the current
   *   user may be allowed to view.
   */
  public function accessNodeIds($link, $nodeAuthorAccount = NULL);
}
