<?php

namespace Drupal\ocms_linkchecker\Plugin\QueueWorker;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\ocms_linkchecker\PupLinkcheckerHttpInterface;
use Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Updates the aquifer content types.
 *
 * @QueueWorker(
 *   id = "ocms_linkchecker_check_links",
 *   title = @Translation("Check for broken links"),
 *   cron = {"time" = 60}
 * )
 */
class PupLinkcheckerCheckLinks extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * The Http service
   * @var Drupal\ocms_linkchecker\PupLinkcheckerHttpInterface
   */
  protected $http;

  /**
   * OCMS Linkchecker Utility Service.
   *
   * @var \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface
   */
  protected $utilityService;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs the Queue Worker
   *
   * @param \Drupal\ocms_linkchecker\PupLinkcheckerHttpInterface $http
   *   The OCMS Linkchecker Http Service.
   * @param \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface $ocms_linkchecker_utility
   *   The OCMS Linkchecker Utility Service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(PupLinkcheckerHttpInterface $http, PupLinkcheckerUtilityInterface $ocms_linkchecker_utility, ConfigFactoryInterface $config_factory) {
    $this->http = $http;
    $this->utilityService = $ocms_linkchecker_utility;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('ocms_linkchecker.http'),
      $container->get('ocms_linkchecker.utility'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    $link = $this->utilityService->loadLink($data);
    if (is_object($link)) {
      $config = $this->configFactory->get('ocms_linkchecker.settings');
      // This link could appear in the queue more than one time if this
      // QueueWorker ran out of time on a previous cron run. If the link
      // is in the queue more than once and things run fast enough that
      // the QueueWorker is able to get to it again within a single cron
      // run, it would get processed in less time than was configured. So,
      // we double check that this link hasn't already been checked within the
      // configured interval on account of it being in the queue more than one
      // time.
      if ($link->last_checked < (REQUEST_TIME - $config->get('check.interval'))) {
        $this->http->checkLink($link);
      }
    }
  }

}
