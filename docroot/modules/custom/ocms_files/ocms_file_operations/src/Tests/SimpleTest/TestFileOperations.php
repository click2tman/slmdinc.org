<?php

namespace Drupal\irs_file_operations\Tests\SimpleTest;

use Drupal\simpletest\WebTestBase;

/**
 * Test the existence of IRS File Operations module.
 *
 * @group irs_file_operations
 */
class TestFileOperations extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('irspup', 'irs_file_operations', 'toolbar', 'media_entity');
  protected $profile = 'testing';

  /**
   *
   * @var \Drupal\user\UserInterface
   */
  protected function setUp() {
    parent::setUp();
  }

  public function testMediaBundleCreation() {

    $directory = 'simpletestpup/ebook/';
    $source = DRUPAL_ROOT . '/media_unpublish/' . $directory;
    $destination = DRUPAL_ROOT . '/sites/default/files/' . $directory;

    $directory_created = file_prepare_directory($destination, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS);

    if ($directory_created) {
      $this->assertTrue($directory_created);

      $files = file_scan_directory($source, '/.*\.(txt|pdf|doc|docx)$/');

      if (!empty($files)) {
        foreach ($files as $file) {
          $result = file_unmanaged_copy($file->uri, $destination, FILE_EXISTS_REPLACE);
          $this->assertTrue($result, "File copied {$result}.");
        }
      }
      else {
        $this->assertTrue(!empty($files), "Files does not exist!");
      }
    }
    else {
      $this->assertTrue($directory_created, "Unable to create directory {$destination}.");
    }
  }

}
