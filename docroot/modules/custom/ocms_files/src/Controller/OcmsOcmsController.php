<?php

namespace Drupal\irspup\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\irspup\Utility\DescriptionTemplateTrait;

/**
 * Simple controller class used to test the DescriptionTemplateTrait.
 */
class IrsPupController extends ControllerBase {
    use DescriptionTemplateTrait;

  /**
   * {@inheritdoc}
   *
   * We override this so we can see some substitutions.
   */
    protected function getModuleName() 
  {
    return 'irspup';
  }

  /**
   * This is the old non-twig version reporting out.
   *
   * 
   */
  //   public function showModuleInfo() {
    
  //   return array(
  //     '#type' => 'markup',
  //     '#markup' => '<h2>IRS OCMS Modules</h2>
  //   <div id="block-seven-content" data-block-plugin-id="system_main_block" class="block block-system block-system-main-block">
  //       <ul class="admin-list">'
  //       .'<li><a href="/admin/config/irspup/irs_file_operations"><span class="label">Static File Operations</span><div class="description">Make the most recent version of a file always retain its original filename.</div></a></li>'
  //       .'<li><a href="/admin/config/irspup/irs_sfbu"><span class="label">SFBU</span><div class="description">Static File Bulk Upload Automatically.</div></a></li>
  //         <li><a href="/admin/config/irspup/irs_customjs"><span class="label">Custom JS</span><div class="description">A module to demonstrate how to insert Javascript.</div></a></li>
  //      </ul></div>
  // </div>
  //     </div>'

  //   );

  //  }

}