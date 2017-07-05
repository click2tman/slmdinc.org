<?php

namespace Drupal\ocms_linkchecker;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Implements the PupLinkcheckerFilteringInterface.
 */
class PupLinkcheckerFilteringService implements PupLinkcheckerFilteringInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs the OCMS Linkchecker Filtering Service object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function checkMarkup($text, $formatId = NULL, $langcode = '') {
    $config = $this->configFactory->get('ocms_linkchecker.settings');
    $skipFilters = array_keys(array_filter($config->get('extract.filter.blacklist')));

    return check_markup($text, $formatId, $langcode, $skipFilters);
  }
}
