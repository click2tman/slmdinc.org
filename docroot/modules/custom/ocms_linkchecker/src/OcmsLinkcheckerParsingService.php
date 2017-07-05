<?php

namespace Drupal\ocms_linkchecker;

use Drupal\Component\Utility\Unicode;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * Implements the PupLinkcheckerParsingInterface.
 */
class PupLinkcheckerParsingService implements PupLinkcheckerParsingInterface {

  /**
   * OCMS Linkchecker Filtering Service.
   *
   * @var \Drupal\ocms_linkchecker\PupLinkcheckerFilteringInterface
   */
  protected $filteringService;

  /**
   * Drupal's entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface;
   */
  protected $entityFieldManager;

  /**
   * Constructs the OCMS Linkchecker Parsing Service object.
   *
   * @param \Drupal\ocms_linkchecker\PupLinkcheckerFilteringInterface $ocms_linkchecker_filtering_service
   *   The OCMS Linkchecker Filtering Service.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface
   *   The entity field manager
   */
  public function __construct(PupLinkcheckerFilteringInterface $ocms_linkchecker_filtering_service, EntityFieldManagerInterface $entity_field_manager) {
    $this->filteringService = $ocms_linkchecker_filtering_service;
    $this->entityFieldManager = $entity_field_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function parseFields($entityType, $bundleName, $entity, $returnFieldNames = FALSE) {
    global $base_url;

    $textItems = array();
    $textItemsByField = array();

    // Create settings for _filter_url() function.
    $filter = new \stdClass();
    $filter->settings['filter_url_length'] = 72;

    // Collect the fields from this entity_type and bundle.
    foreach ($this->entityFieldManager->getFieldDefinitions($entityType, $bundleName) as $fieldName => $instance) {
      $field = FieldStorageConfig::loadByName($entityType, $fieldName);
      if (isset($field)) {
        $type = $field->get('type');
      }
      else {
        $type = '';
      }

      switch ($type) {
        // Core fields.
        case 'text_with_summary':
              $fieldValues = $entity->get($fieldName)->getValue();
              foreach ($fieldValues as $item) {
                $item += array(
                  'format' => NULL,
                  'summary' => '',
                  'value' => '',
                );
                $languageCode = $entity->language()->getId();
                $textItems[] = $textItemsByField[$fieldName][] = $this->filteringService->checkMarkup($item['value'], $item['format'], $languageCode, TRUE);
                $textItems[] = $textItemsByField[$fieldName][] = $this->filteringService->checkMarkup($item['summary'], $item['format'], $languageCode, TRUE);
              }
          break;

        // Core fields.
        case 'text':
        case 'text_long':
              $fieldValues = $entity->get($fieldName)->getValue();
              foreach ($fieldValues as $item) {
                $item += array(
                  'format' => NULL,
                  'value' => '',
                );
                $languageCode = $entity->language()->getId();
                $textItems[] = $textItemsByField[$fieldName][] = $this->filteringService->checkMarkup($item['value'], $item['format'], $languageCode, TRUE);
	      }
          break;

        case 'link':
            $fieldValues = $entity->get($fieldName)->getValue();
            foreach ($fieldValues as $item) {
              $item += array(
                'title' => '',
              );
              $languageCode = $entity->language()->getId();
              $options = UrlHelper::parse($item['uri']);
	      // Force this to look like an external URL so that ::fromUri won't
              // change it from how it's entered.
              if (!UrlHelper::isExternal($options['path'])) {
                $options['path'] = $base_url . str_replace(['entity:', 'internal:'], ['/', ''], $item['uri']);
              }
              $url = Url::fromUri($options['path'], $options);
              $textItems[] = $textItemsByField[$fieldName][] = Link::fromTextAndUrl($item['title'], $url)->toString();
            }
          break;
      }
    }

    return ($returnFieldNames) ? $textItemsByField : $textItems;
  }

  /**
   * {@inheritdoc}
   */
  public function getAbsoluteUrl($url) {
    // Parse the URL and make sure we can handle the schema.
    $uri = @parse_url($url);

    if ($uri == FALSE) {
      return NULL;
    }

    if (!isset($uri['scheme'])) {
      return NULL;
    }

    // Break if the schema is not supported.
    if (!in_array($uri['scheme'], array('http', 'https'))) {
      return NULL;
    }

    $scheme = $uri['scheme'] . '://';
    $user = isset($uri['user']) ? $uri['user'] . ($uri['pass'] ? ':' . $uri['pass'] : '') . '@' : '';
    $port = isset($uri['port']) ? $uri['port'] : 80;
    $host = $uri['host'] . ($port != 80 ? ':' . $port : '');

    // Glue the URL variables.
    $absoluteUrl = $scheme . $user . $host . '/';

    return $absoluteUrl;
  }
}
