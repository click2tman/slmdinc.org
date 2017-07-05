<?php

namespace Drupal\Tests\irs_file_operations\Kernel;

use Drupal\Tests\irs_file_operations;
use Drupal\Core\Language\Language;
use Drupal\Core\Entity;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * Tests token handling.
 *
 * @requires module token
 * @requires module entity
 *
 * @group media_entity
 */
class TestFileOperations extends EntityKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
  }

  public function testMediaBundleCreation() {
    $fid = 1;

    $directory = 'simpletestpup/ebook/';
    $source = DRUPAL_ROOT . '/media_unpublish/' . $directory;
    $destination = DRUPAL_ROOT . '/sites/default/files/' . $directory . 'tmp';
    
    $directory_created = file_prepare_directory($destination, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS);
    
    if($directory_created) {
      $this->assertTrue($directory_created);

      $files = file_scan_directory($source, '/.*\.(txt|pdf|doc|docx)$/');

      if(!empty($files)) {
        foreach ($files as $file) {
          $result = file_unmanaged_copy($file->uri, $destination, FILE_EXISTS_REPLACE);
          $this->assertTrue($result,"Unable to copy {$file->uri} to $destination.");
        }
      }
      else {
        $this->assertTrue(!empty($files), "Files does not exist!");
      }

    }
    else {
      $this->assertTrue($directory_created, "Unable to create directory $destination.");
    }
    
    
  }

  /**
   * Tests some of the tokens provided by media_entity.
   */
  

}
