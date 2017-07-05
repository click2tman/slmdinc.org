<?php

namespace Drupal\irs_file_operations\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\irspup\Utility\DescriptionTemplateTrait;

/**
 *  @file
 *  Controller for media_helper module.
 */
class IrsFileOperationsController extends ControllerBase {

  use DescriptionTemplateTrait;

  /**
   * Move file to destination
   */
 public function moveFileToDestination($fid, $destination) {
    $file_storage = \Drupal::entityManager()->getStorage('file');

    if (empty($destination)) {
      \Drupal::logger('irs_file_operations')->error($destination . 'Destination cannot be empty');
    }

    $file = $file_storage->load($fid);
    $file_uri = $file->get('uri')->value;
    $file_name = $file->get('filename')->value;
    $query = \Drupal::database()->update('file_managed');
 
    $query->fields([
      'uri' => $destination . $file_name,
    ]);
    
    $query->condition('fid', $fid);
    $query->execute();
    db_truncate('cache_entity')->execute();
    $file_prepare = file_prepare_directory($destination, FILE_CREATE_DIRECTORY);
    
    if ($file_prepare == FALSE) {
      \Drupal::logger('irs_file_operations')->error($file_prepare . 'Directory does not exist and it is not creted.');
    }
    $file_move = file_move($file, $destination, FILE_EXISTS_REPLACE);
    
    if ($file_move == FALSE) {
      \Drupal::logger('irs_file_operations')->error($file_move . 'File is not moved');
    }

    return drupal_flush_all_caches();
  }

  /**
   * Update location path based on folder repository field.
   */
  public function irsFileOperationsGetTax($termid) {
    $storage = \Drupal::service('entity_type.manager')->getStorage('taxonomy_term');
    $parents = $storage->loadAllParents($termid);
    $termname_result = '';
    foreach (array_reverse($parents) as $parent) {
      $termname = irs_file_operations_clean_string($parent->get('name')->value) . '/';
      $termname_result .= $termname;
    }
	// remove the last '/'
	$termname_result=substr_replace($termname_result, "", -1);
    return $termname_result;
  }

  /**
   * Returns URI for the Particular Media
   */
  public function irsFileOperationsUri($fid) {
    $query = \Drupal::database()->select('file_managed', 'fd');
    $query->addField('fd', 'uri');
    $query->condition('fd.fid', $fid);
    $query->range(0, 1);
    $location_path_value = $query->execute()->fetchField();
    return $location_path_value;
  }

  /**
   * {@inheritdoc}
   *
   * We override this so we can see some substitutions.
   */
  protected function getModuleName() {
    return 'irs_file_operations';
  }

}
