<?php

namespace Drupal\ocms_taxonomy_breadcrumb;

use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Component\Utility\Unicode;
use Drupal\system\PathBasedBreadcrumbBuilder;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Breadcrumb\Breadcrumb;

/**
 * Adds the current page title and navigation taxonomy terms to the breadcrumb.
 *
 *
 * {@inheritdoc}
 */
class BreadcrumbBuilder extends PathBasedBreadcrumbBuilder {

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumbs = parent::build($route_match);
      //check if its a node
      $node = \Drupal::routeMatch()->getParameter('node');
      if (!$node ){
        //echo "not a node ";
       
      }else{
		 
        $config = \Drupal::config('ocms_taxonomy_breadcrumb.configuration');
        $breadcrumb_taxonomy=$config->get('breadcrumb_taxonomy');
       
	    $tid=null;
		$nav=null;
        switch ($breadcrumb_taxonomy){
		 case "navigation":
		  if(isset($node->field_ocms_navigation)){
		     $nav=$node->field_ocms_navigation;
		  }	
		  break;
		 case "channels":
		  if(isset($node->field_ocms_channel)){
		      $nav=$node->field_ocms_channel;
		  }
          break;		  
		 
		}
		if ($nav){ 
          $nav_value=$nav->getValue();
          $tid=$nav_value[0]['target_id'];
		  
          if ($tid){
            $term = Term::load($tid);
            $term_name = $term->getName();
			
            $ancestors = \Drupal::service('entity_type.manager')->getStorage("taxonomy_term")->loadAllParents($tid);
            $parents=array();
            $list = [];
            $x=0;

            foreach ($ancestors as $term) {
              $this_name=$term->getName();
              if ($term_name != $this_name){
                $pid=$term->id();
                $parents[$pid]=$term->getName();
              
              }
          
            }
			$parents=array_reverse($parents);
          }  // end if tid

		} // end if navigation

      }  // end if node
    //}
    $request = \Drupal::request();
    $path = trim($this->context->getPathInfo(), '/');
    $path_elements = explode('/', $path);
    $route = $request->attributes->get(RouteObjectInterface::ROUTE_OBJECT);

    // Do not adjust the breadcrumbs on admin paths.
    if ($route && !$route->getOption('_admin_route')) {
      $title = $this->titleResolver->getTitle($request, $route);
      if (!isset($title)) {

        // Fallback to using the raw path component as the title if the
        // route is missing a _title or _title_callback attribute.
        $title = str_replace(array('-', '_'), ' ', Unicode::ucfirst(end($path_elements)));
      }
      if ($tid){
     $breadcrumb=new Breadcrumb();
      $home_link="Home";
      
        $breadcrumb->addLink(Link::createFromRoute($home_link,'<front>'));
      
        foreach ($parents as $key=>$value){
          $breadcrumb->addLink(Link::createFromRoute($value, "entity.taxonomy_term.canonical", ["taxonomy_term" => $key]));
        }
        $breadcrumb->addLink(Link::createFromRoute($term_name, "entity.taxonomy_term.canonical", ["taxonomy_term" => $tid]));
      }else{
      $breadcrumb=$breadcrumbs;
    }
      
      //$breadcrumbs->addLink(Link::createFromRoute($title, '<none>'));
    }else{
    $breadcrumb=$breadcrumbs;
  }

    // Add the full URL path as a cache context, since we will display the
    // current page as part of the breadcrumb.
    //$breadcrumbs->addCacheContexts(['url.path']);

    return $breadcrumb;
  }

}