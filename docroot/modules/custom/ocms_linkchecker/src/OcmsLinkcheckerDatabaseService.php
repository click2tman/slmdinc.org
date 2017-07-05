<?php

namespace Drupal\ocms_linkchecker;

use Drupal\Component\Utility\Crypt;
use Drupal\Core\Database\Connection;
use Drupal\node\NodeInterface;

/**
 * Implements the PupLinkcheckerDatabaseInterface
 */
class PupLinkcheckerDatabaseService implements PupLinkcheckerDatabaseInterface {

  /**
   * OCMS Linkchecker Extraction Service.
   *
   * @var \Drupal\ocms_linkchecker\PupLinkcheckerExtractionInterface
   */
  protected $extractionService;

  /**
   * OCMS Linkchecker Utility Service.
   *
   * @var \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface
   */
  protected $utilityService;

  /**
   * Drupal's database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs the OCMS Linkchecker Database Service object.
   *
   * @param \Drupal\ocms_linkchecker\PupLinkcheckerExtractionInterface $ocms_linkchecker_extraction
   *   The OCMS Linkchecker Extraction Service.
   * @param \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface $ocms_linkchecker_utility
   *   The OCMS Linkchecker Utility Service.
   * @param \Drupal\Core\Database\Connection
   *   The database connection
   */
  public function __construct(PupLinkcheckerExtractionInterface $ocms_linkchecker_extraction, PupLinkcheckerUtilityInterface $ocms_linkchecker_utility, Connection $database) {
    $this->extractionService = $ocms_linkchecker_extraction;
    $this->utilityService = $ocms_linkchecker_utility;
    $this->connection = $database;
  }

  /**
   * {@inheritdoc}
   */
  public function addNodeLinks($node, $skipMissingLinksDetection = FALSE) {
    $links = array_keys($this->extractionService->extractNodeLinks($node));

    // The node had links
    if (!empty($links)) {
      // Remove all links from the links array already in the database and only
      // add missing links to database.
      $missingLinks = $this->getMissingNodeReferences($node, $links);

      // Only add links to database that do not exists.
      $i = 0;
      // @todo: Make this a configurable value
      $maxLinksLimit = 100;
      foreach ($missingLinks as $url) {
        $urlhash = Crypt::hashBase64($url);
        $link = $this->connection->query('SELECT lid FROM {ocms_linkchecker_link} WHERE urlhash = :urlhash', array(':urlhash' => $urlhash))->fetchObject();
        if (!$link) {
          $link = new \stdClass();
          $link->lid = $this->connection->insert('ocms_linkchecker_link')
            ->fields(array(
              'urlhash' => $urlhash,
              'url' => $url,
              'status' => $this->utilityService->shouldCheckLink($url)
            ))
            ->execute();
        }

        $this->connection->insert('ocms_linkchecker_entity')
          ->fields(array(
            'entity_id' => $node->nid->value,
            'entity_type' => 'node',
            'bundle' => $node->bundle(),
            'langcode' => $node->language()->getId(),
            'lid' => $link->lid,
          ))
          ->execute();

        // Break processing if max links limit per run has been reached.
        $i++;
        if ($i >= $maxLinksLimit) {
          break;
        }
      }

      // The first chunk of links not yet found in the {ocms_linkchecker_link}
      // table have now been imported by the above code. If the number of
      // missing links still exceeds $maxLinksLimit the content need to be
      // re-scanned until all links have been collected and saved in
      // {ocms_linkchecker_link} table.
      //
      // Above code has already scanned a number of $maxLinksLimit links and
      // need to be  substracted from the number of missing links to calculate
      // the correct number of re-scan rounds.
      //
      // To prevent endless loops the $skipMissingLinksDetection need to be
      // TRUE. This value will be set by the calling batch process that already
      // knows that it is running a batch job and the number of required re-scan
      // rounds.
      $missingLinksCount = count($missingLinks) - $maxLinksLimit;
      if (!$skipMissingLinksDetection && $missingLinksCount > 0) {
        // @todo: I'm not worrying about processing in batch right now.
        //        Just fire of something to load some links.
        //module_load_include('inc', 'linkchecker', 'linkchecker.batch');
        //batch_set(_linkchecker_batch_import_single_node($node->nid->value, $missing_links_count));

        // If batches were set in the submit handlers, we process them now,
        // possibly ending execution. We make sure we do not react to the batch
        // that is already being processed (if a batch operation performs a
        // drupal_execute).
        //if ($batch = &batch_get() && !isset($batch['current_set'])) {
          //batch_process('node/' . $node->nid->value);
        //}
      }
    }

    // Remove dead link references for cleanup reasons as very last step.
    $this->deleteExpiredNodeReferences($node, $links);
  }

  /**
   * {@inheritdoc}
   */
  public function addCommentLinks($comment, $skipMissingLinksDetection = FALSE) {
  }

  /**
   * {@inheritdoc}
   */
  public function addBlockLinks($block, $bid, $skipMissingLinksDetection = FALSE) {
  }

  /**
   * {@inheritdoc}
   */
  public function deleteNodeLinks(NodeInterface $node) {
    $this->connection->delete('ocms_linkchecker_entity')
      ->condition('entity_id', $node->nid->value)
      ->condition('entity_type', 'node')
      ->condition('langcode', $node->language()->getId())
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function setBrokenNodeReferences(NodeInterface $node) {
    if ($node->language()) {
      $url = $node->toUrl('canonical', ['absolute' => true, 'language' => $node->language()]);
      if ($node->language()->isDefault()) {
        $permaLink = '/node/' . $node->nid->value;
      }
      else {
	$permaLink = '/' . $node->language()->getId() . '/node/' . $node->nid->value;
      }
    }
    else {
      $url = $node->toUrl('canonical', ['absolute' => true]);
      $permaLink = '/node/' . $node->nid->value;
    }
    $aliasedUrl = $url->toString();
    $this->connection->update('ocms_linkchecker_link')
      ->fields(array(
	'code' => 404,
	'error' => 'Not Found',
	'fail_count' => 1,
	'last_checked' => REQUEST_TIME
      ))
      ->condition('url', $aliasedUrl)
      ->execute();
    // This is just being extra careful to make sure that both the aliased and
    // unaliased links are marked as broken. Some pieces of content may be
    // referencing this node with its alias and others with is "permalink."
    // Unfortunately, we don't have a guaranteed way to know about any
    // past aliases that this node had. Those old aliases will just have to
    // work their way through the Broken Links report.
    $uri = @parse_url($aliasedUrl);
    if ($uri['path'] != $permaLink) {
      $unaliasedUrl = str_replace($uri['path'], $permaLink, $aliasedUrl);
      $this->connection->update('ocms_linkchecker_link')
        ->fields(array(
        'code' => 404,
        'error' => 'Not Found',
        'fail_count' => 1,
        'last_checked' => REQUEST_TIME
       ))
       ->condition('url', $unaliasedUrl)
       ->execute();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function deleteCommentLinks($comment) {
    $this->connection->delete('ocms_linkchecker_entity')
      ->condition('entity_id', $comment->cid->value)
      ->condition('entity_type', 'comment')
      ->condition('langcode', $comment->language()->getId())
      ->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function deleteBlockLinks($block) {
  }

  /**
   * {@inheritdoc}
   */
  public function deleteExpiredNodeReferences(NodeInterface $node, $links = array()) {
    if (empty($links)) {
      // Node do not have links. Delete all references if exists.
      $this->connection->delete('ocms_linkchecker_entity')
        ->condition('entity_id', $node->nid->value)
        ->condition('entity_type', 'node')
        ->condition('langcode', $node->language()->getId())
        ->execute();
    }
    else {
      // The node still have more than one link, but other links may have been
      // removed and links no longer in the content need to be deleted from the
      // linkchecker_node reference table.
      // @todo: Switch to using the Utility service
      $urlsHashed = array();
      foreach ($links as $link) {
        $urlsHashed[] = Crypt::hashBase64($link);
      }
      $keepLinks = $this->connection->select('ocms_linkchecker_link')
        ->fields('ocms_linkchecker_link', array('lid'))
        ->condition('urlhash', $urlsHashed, 'IN')
        ->execute()
        ->fetchAllKeyed(0,0);

      $this->connection->delete('ocms_linkchecker_entity')
        ->condition('entity_id', $node->nid->value)
        ->condition('entity_type', 'node')
        ->condition('langcode', $node->language()->getId())
        ->condition('lid', $keepLinks, 'NOT IN')
        ->execute();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function deleteExpiredCommentReferences($comment, $links = array()) {
  }

  /**
   * {@inheritdoc}
   */
  public function deleteBlockReferences($block, $links = array()) {
  }

  /**
   * {@inheritdoc}
   */
  public function getMissingNodeReferences(NodeInterface $node, $links) {
    // @todo: Switch to using the Utility service
    $urlsHashed = array();
    foreach ($links as $link) {
      $urlsHashed[] = Crypt::hashBase64($link);
    }
    $keepLinks = $this->connection->select('ocms_linkchecker_link', 'll');
    $keepLinks->innerJoin('ocms_linkchecker_entity', 'le', 'le.lid = ll.lid');
    $keepLinks->fields('ll', array('url'));
    $keepLinks->condition('ll.urlhash', $urlsHashed, 'IN');
    $keepLinks->condition('le.entity_id', $node->nid->value);
    $keepLinks->condition('le.entity_type', 'node');
    $keepLinks->condition('le.langcode', $node->language()->getId());
    $results=$keepLinks->execute()->fetchAllKeyed(0,0);
    $linksInDatabase = array();
    foreach ($results as $row) {
      $linksInDatabase[] = $row;
    }
    return array_diff($links, $linksInDatabase);
  }

  /**
   * {@inheritdoc}
   */
  public function getMissingCommentReferences($comment, $links) {
  }

  /**
   * {@inheritdoc}
   */
  public function getMissingBlockReferences($block, $links) {
  }

  /**
   * {@inheritdoc}
   */
  public function unpublishNodes($lid) {
  }
}
