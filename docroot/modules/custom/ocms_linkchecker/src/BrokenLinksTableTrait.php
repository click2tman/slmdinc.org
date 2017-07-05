<?php

namespace Drupal\ocms_linkchecker;

use Drupal\Component\Utility\Html;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides logic to build the table of broken links.
 */
trait BrokenLinksTableTrait {

  /**
   * Builds the broken links report table with pager.
   */
  public function build() {

  $links_unchecked = $this->connection->query('SELECT COUNT(1) FROM {ocms_linkchecker_link} WHERE last_checked = :last_checked AND status = :status', array(':last_checked' => 0, ':status' => 1))->fetchField();
  if ($links_unchecked > 0) {
    $links_all = $this->connection->query('SELECT COUNT(1) FROM {ocms_linkchecker_link} WHERE status = :status', array(':status' => 1))->fetchField();
    drupal_set_message($this->translation->formatPlural($links_unchecked,
      'There is 1 unchecked link of about @links_all links in the database. Please be patient until all links have been checked via cron.',
      'There are @count unchecked links of about @links_all links in the database. Please be patient until all links have been checked via cron.',
      array('@links_all' => $links_all)), 'warning');
  }

    $query = $this->query;

    $header = array(
      array('data' => $this->t('URL'), 'field' => 'url', 'sort' => 'desc'),
      array('data' => $this->t('Response'), 'field' => 'code', 'sort' => 'desc'),
      array('data' => $this->t('Error'), 'field' => 'error'),
      array('data' => $this->t('Operations')),
    );

    $result = $query
      ->limit(50)
      ->orderByHeader($header)
      ->execute();

    // Evaluate permission once for performance reasons.
    $accessEditLinkSettings = $this->currentUser->hasPermission('edit link settings');
    $accessAdministerBlocks = $this->currentUser->hasPermission('administer blocks');
    $accessAdministerRedirects = $this->currentUser('administer redirects');

    $rows = array();
    foreach ($result as $link) {
      // Get the node, block and comment IDs that refer to this broken link and
      // that the current user has access to.
      $nids = $this->access->accessNodeIds($link, $this->currentUser);
//    $cids = _linkchecker_link_comment_ids($link, $this->currentUser);
//    $bids = _linkchecker_link_block_ids($link);

    // If the user does not have access to see this link anywhere, do not
    // display it, for reasons explained in _linkchecker_link_access(). We
    // still need to fill the table row, though, so as not to throw off the
    // number of items in the pager.
//    if (empty($nids) && empty($cids) && empty($bids)) {
    if (empty($nids)) {
        $rows[] = array(array('data' => $this->t('Permission restrictions deny you access to this broken link.'), 'colspan' => count($header)));
        continue;
      }

      $links = array();

      // Show links to link settings.
      if ($accessEditLinkSettings) {
	$url = Url::fromRoute('ocms_linkchecker.edit_link', array('linkId' => $link->lid), array('query' => $this->redirectDestination->getAsArray()));
        $links[] = Link::fromTextAndUrl($this->t('Edit link settings'), $url)->toString();
      }

      // Show link to nodes having this broken link.
      foreach ($nids as $nid) {
        $url = Url::fromUri('internal:/node/' . $nid . '/edit', array('query' => $this->redirectDestination->getAsArray()));
        $links[] = Link::fromTextAndUrl($this->t('Edit node @node', array('@node' => $nid)), $url)->toString();
      }

    // Show link to comments having this broken link.
//    $comment_types = linkchecker_scan_comment_types();
//    if (module_exists('comment') && !empty($comment_types)) {
//      foreach ($cids as $cid) {
//        $links[] = l(t('Edit comment @comment', array('@comment' => $cid)), 'comment/' . $cid . '/edit', array('query' => drupal_get_destination()));
//      }
//    }

    // Show link to blocks having this broken link.
//    if ($accessAdministerBlocks) {
//      foreach ($bids as $bid) {
//        $links[] = l(t('Edit block @block', array('@block' => $bid)), 'admin/structure/block/manage/block/' . $bid . '/configure', array('query' => drupal_get_destination()));
//      }
//    }

    // Show link to redirect this broken internal link.
//    if (module_exists('redirect') && $access_administer_redirects && _linkchecker_is_internal_url($link)) {
//      $links[] = l(t('Create redirection'), 'admin/config/search/redirect/add', array('query' => array('source' => $link->internal, drupal_get_destination())));
//    }

      $url = Url::fromUri($link->url);
      // Create table data for output.
      $rows[] = array(
        'data' => array(
          Link::fromTextAndUrl(Html::escape($link->url), $url)->toString(),
          Html::escape($link->code),
          Html::escape($link->error),
          ['data' => ['#theme' => 'item_list', '#items' => $links]],
        ),
      );
    }

    $build['broken_links_table'] = array(
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No broken links have been found.'),
    );
    $build['broken_links_pager'] = array('#type' => 'pager');

    // I think I may not need to set cache meta data
    // @todo: How do I want to cache this page?
    //        Will I use cache tags, cache contexts, both of them?
    //$build['#cache']['tags'][] = 'node_list';
    return $build;
  }
}
