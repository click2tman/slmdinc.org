<?php

namespace Drupal\ocms_viewfiles\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity;
use Drupal\media_entity\Entity\Media;
use Drupal\file\Entity\File;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Database\Connection;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *  @file
 *  Controller for media_helper module.
 */
class IrsFileView extends ControllerBase { 

  /**
   * View directory folders under sites files which are in our static file taxonomy 
   */
 public function content() {
  $vid="ocms_static_file_path";
  $parent = 0;
  $max_depth = NULL;
  $load_entities = FALSE;
  $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid, $parent, $max_depth, $load_entities);
  $directory_names=array();
  $headers = array(
      array('data' => t('Name'))
  
  
      );  
  foreach ($terms as $term){
    $directory_names[]=$term->name;
  }
  $directory_tree="This directory listing is considered an expert interface to locating PDF documents. Please refer to the <a href='https://www.ocms.gov/Forms-&-Pubs'>main forms and publications landing page </a> for more details on locating specific forms, publications, or instructions.<br><br>";
  $path = "sites/default/files/";
  $directories = scandir($path);
  if (count($directories)){
    foreach ($directories as $directory) {
      if (in_array($directory,$directory_names)){
        if ($directory !="images"){
         $link_url= "/downloads/$directory";
         $host = \Drupal::request()->getSchemeAndHttpHost();
         $full_path = $host."/sites/default/files/".$directory;
         $directoryData[] = array(
                'link_url' => $link_url,
                'name' => $directory,
               
                
              );
        }
       }  
    }
  }  
    
 $order = tablesort_get_order($headers);

 $rows = array();
 foreach ($directoryData as $entry) {
   $rows[] = array(
       
       array('data' => new FormattableMarkup('<a href=":link">@name</a>', 
          [':link' => $entry['link_url'], 
          '@name' => $entry['name']])
        ),
       
       
        
      );
 }

   
   $attributes = array('class' => array('tablesorter'),'id'=>array("myTable"));
   $table_element = array(
        '#theme' => 'table',
        '#header' => $headers,
        '#rows' => $rows,
        '#empty' =>t('Your table is empty'),
        '#attributes'=>$attributes,
        
    );  
    $html=drupal_render($table_element);
    $html=$directory_tree.$html;

    $build = array(
          '#type' => 'markup',
          '#markup' => $html,
          '#attached' => array(
          
             'library' => array('ocms_viewfiles/sortfiles_libraries'),
            ),
        );

    return $build;
 
  }
  
  /**
   * View directory folders
   */
   
  public function dir_content($path_dir='') {
	// get the complete path which may be several levels deep
  $current_path = \Drupal::service('path.current')->getPath();
  $path_dir=str_replace("/downloads/","",$current_path);
  $path = "sites/default/files/$path_dir";
  // get the taxonoym id for this path 
  $vid="ocms_static_file_path";
  $file_path_tid=getTidByName($path_dir,$vid);
  $directoryData=array(); 
  
  $parent_dirs=explode("/",$path_dir);
  $last_element=array_pop($parent_dirs);
  $parent_url=implode("/",$parent_dirs);
  $host = \Drupal::request()->getSchemeAndHttpHost();
  $file_names = scandir($path);
  if (count($file_names)) {
    foreach ($file_names as $file_name) {
     // find out if this is a file or a directory
     if ($file_name!="." AND $file_name!="..") {
       $array = explode('.', $file_name);
       if (count($array)>1){
            $extension = end($array);
       }else{
         $extension ="";
       }
       if (!$extension){
        // if it doesnt have any exension it must be a directory 
        $link_url= "/downloads/$path_dir/".$file_name;
        $directoryData[] = array(
                'link_url' => $link_url,
                'name' => $file_name,
                'changed_date' => "",
                'size' => "",
                'description' => ""
              );
       
       }
	} 
	}
  }
  $rows = array();
   foreach ($directoryData as $entry) {
     $dir_rows[] = array(
       array('data' => new FormattableMarkup('<a href=":link">@name</a>', 
          [':link' => $entry['link_url'], 
          '@name' => $entry['name']])
        ),
         
      );
   }
   
   if (count($directoryData)){
	 
	  $directory_headers = array(
         array('data' => t('Name'))
  
  
      ); 
	  $dir_table_element = array(
        '#theme' => 'table',
       
        '#rows' => $dir_rows,
       
        
    );  
    $dir_html=drupal_render($dir_table_element);
	
   }else{
	   $dir_html="";
   }


  
  $headers = array(
     array('data' => t('Name'),'field' => 'filename', 'sort' => 'asc' ),
     array('data' => t('Date'), 'field' => 'changed','sort' => 'asc'),
     array('data' => t('Size'), 'field' => 'filesize','sort' => 'asc'),
     array('data' => t('Description'), 'field' => 'name','sort' => 'asc'),
    );  
	
   $db = \Drupal::database(); 
	   
   $query = $db->select('media_field_data', 'mf_data')
      ->extend('\Drupal\Core\Database\Query\PagerSelectExtender')
      ->extend('\Drupal\Core\Database\Query\TableSortExtender'); 
	  $query->leftJoin('media__field_ocms_posted', 'posted', 'mf_data.mid = posted.entity_id');
	  $query->leftJoin('media__field_document', 'mf_doc', 'mf_data.mid = mf_doc.entity_id');
	  $query->innerJoin('media_field_data','mfd','mfd.mid = mf_data.mid');
	  $query->innerJoin('file_managed', 'fm', 'mf_doc.field_document_target_id = fm.fid');
	  $query->innerJoin('media__field_ocms_file_public_path', 'pp', 'mf_data.mid = pp.entity_id');
	  $query->fields('fm',array('filename','uri','changed','filesize'));
	  $query->fields('mfd',array('name'));
	  //$query->fields('posted',array('name')); 
	  $query->condition('pp.field_ocms_file_public_path_target_id', $file_path_tid, '=');
	  $db_or = db_or();
      $db_or->condition('mf_data.moderation_state', 'published', '=');
	  $db_or->condition('mf_data.moderation_state', NULL, 'IS');
      $query->condition($db_or);
	 

	  $result = $query
      ->limit(50)
      ->orderByHeader($headers)
      ->execute();

	    
   $host = \Drupal::request()->getSchemeAndHttpHost();
     $full_path = $host."/sites/default/files/";    
   // Populate the rows.
   $rows = array();
   foreach($result as $row) {
	   /*
	   $post_data=null;
       if (isset($static_media->field_ocms_posted)){
                 
          $posted_date=$static_media->get('field_ocms_posted')->getValue();
          $post_date=$posted_date[0]['value'];
       }
                 
     if ($post_date){
                 $changed_date= str_replace("T"," ",$post_date);
                }else{
                 $changed_date= date("Y-m-d\ H:i:s", $timestamp);
       }
	   */
	// only show files that are published in the public directory
	//if(strpos($row->uri,"public://") !== false){
	 $link=str_replace("public://",$full_path,$row->uri);
	 $size = format_size($row->filesize);
     $rows[] = array('data' => array(
	 'Name'=> new FormattableMarkup('<a href=":link">@name</a>', 
          [':link' => $link, 
          '@name' => $row->filename])
        ,
   
	  'Date' =>date('Y-m-d\ H:i:s',$row->changed),
	  'Size' =>$size,
      'Description' => $row->name,
    ));
	//}
   }
  
  
  $parent_dirs=explode("/",$path_dir);
  $last_element=array_pop($parent_dirs);
  $parent_url=implode("/",$parent_dirs);
  $host = \Drupal::request()->getSchemeAndHttpHost();
  $file_names = scandir($path);
  // different text for irm directory
  if ($path_dir=="irm") {
      $directory_tree="IRM Source Files<br>This page provides links to the Internal Revenue Manual (IRM) source files. 
      The listing can be sorted by the file name or the date the file was posted. To locate forms, instructions and publications 
      please see the <a href='/forms-pubs'>Forms & Publications page</a>.<br>";

  } else {
      $directory_tree="Contents of Directory $path_dir<br><br>";
  }
  $directory_tree.="<br><a href='/downloads/$parent_url'>Parent Directory</a><br><br>"; 
  if (count($file_names)) {
    // headers array, sorting by default on filename
    
    $directoryData=array();   
    foreach ($file_names as $file_name) {
     // find out if this is a file or a directory
     if ($file_name!="." AND $file_name!="..") {
       $array = explode('.', $file_name);
       if (count($array)>1){
            $extension = end($array);
       }else{
         $extension ="";
       }
       if (!$extension){
        // if it doesnt have any exension it must be a directory 
        $link_url= "/downloads/$path_dir/".$file_name;
        $directoryData[] = array(
                'link_url' => $link_url,
                'name' => $file_name,
                'changed_date' => "",
                'size' => "",
                'description' => ""
              );
       
       }
	 } 
	}
  }

    $table_element = array(
        '#theme' => 'table',
        '#header' => $headers,
        '#rows' => $rows,
        '#empty' =>t('No files found'),
        
        
    );  
 
    $html=drupal_render($table_element);
    $html=$directory_tree.$dir_html.$html;
   
	
	  $build = array(
	      
          '#type' => 'markup',
          '#markup' => $html,
          
        );
		
	
    $build['file_table'] = array('#type' => 'pager');

    return $build;
  }


/**
   * Utility: find term by name and vid.
   * @param null $name
   *  Term name
   * @param null $vid
   *  Term vid
   * @return int
   *  Term id or 0 if none.
   */
   function getTidByName($name = NULL, $vid = NULL) {
    $properties = [];
    if (!empty($name)) {
      $properties['name'] = $name;
    }
    if (!empty($vid)) {
      $properties['vid'] = $vid;
    }
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadByProperties($properties);
    $term = reset($terms);

    return !empty($term) ? $term->id() : 0;
  }


  protected function getModuleName() {
    return 'ocms_viewfiles';
  }
  
  function is_this_directory ($file){ 
    return ((fileperms("$file") & 0x4000) == 0x4000);
  }
  
 
}
