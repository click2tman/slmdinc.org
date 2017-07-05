<?php

namespace Drupal\irs_file_operations\Tests\Unit;

use Drupal\Tests\UnitTestCase;


if (!defined('DRUPAL_ROOT')) {
  //Looping to find drupal root folder.
  $current_dir = dirname(__DIR__);
  while (!file_exists("$current_dir/index.php")) {
    $current_dir = dirname($current_dir);
  }
  define('DRUPAL_ROOT', $current_dir);
}

/**
 * Tests the Drupal 8 irspup module functionality
 *
 * @group irspup
 */
class TestFileOperations extends UnitTestCase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = array('irs_file_operations', 'irspup', 'toolbar');
  protected $profile = 'testing';

    /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
  }

/**
   * Tests creating a media bundle programmatically.
   */
  public function testMediaBundleCreation() {

    $directory = 'simpletestpup/ebook/';
    $source = DRUPAL_ROOT . '/media_unpublish/' . $directory;
    $destination = DRUPAL_ROOT . '/sites/default/files/' . $directory;
    
    $directory_created = false;
    if (!file_exists($destination)) {
      $directory_created = mkdir($destination, 0777, true);
    }

    $files = glob($source . "*.{pdf,txt,doc,docx}", GLOB_BRACE);

    if(!empty($files)) {
      foreach ($files as $file) {
        $file_name_array = explode("/", $file);
        $count = sizeof($file_name_array);
        $file_name = $file_name_array[$count - 1];
        $result = copy($file, $destination . $file_name); 
        $this->assertTrue($result,"File copied.");
      }
    }
    else {
      $this->assertTrue(!empty($files), "Files does not exist!");
	}
	
  }
  
} 