<?php

namespace Drupal\facets\FacetSource;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manages facet source plugins.
 *
 * @see \Drupal\facets\Annotation\FacetsFacetSource
 * @see \Drupal\facets\FacetSource\FacetSourcePluginBase
 * @see plugin_api
 */
class FacetSourcePluginManager extends DefaultPluginManager {

  /**
   * {@inheritdoc}
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/facets/facet_source', $namespaces, $module_handler, 'Drupal\facets\FacetSource\FacetSourcePluginInterface', 'Drupal\facets\Annotation\FacetsFacetSource');
  }

  /**
   * {@inheritdoc}
   */
  public function processDefinition(&$definition, $plugin_id) {
    parent::processDefinition($definition, $plugin_id);

    // At the very least - we need to have an ID in the definition of the
    // plugin.
    if (!isset($definition['id'])) {
      throw new PluginException(sprintf('The facet source plugin %s must define the id property.', $plugin_id));
    }

    // If we're checking the search api plugin, only try to add it if search api
    // is enabled.
    if ($definition['id'] === 'search_api' && !$this->moduleHandler->moduleExists('search_api')) {
      return;
    }

    // Check that other required labels are available.
    foreach (['display_id', 'label'] as $required_property) {
      if (empty($definition[$required_property])) {
        throw new PluginException(sprintf('The facet source plugin %s must define the %s property.', $plugin_id, $required_property));
      }
    }
  }

}
