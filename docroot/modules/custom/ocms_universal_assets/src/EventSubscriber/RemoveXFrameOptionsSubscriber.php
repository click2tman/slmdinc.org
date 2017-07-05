<?php

namespace Drupal\ocms_universal_assets\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Removes the X-Frame-Options to allow iframe embedding.
 */
class RemoveXFrameOptionsSubscriber implements EventSubscriberInterface {

  /**
   * Update response headers based on path.
   *
   * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
   *   Response event.
   */
  public function updateResponseHeaders(FilterResponseEvent $event) {
    $response = $event->getResponse();
    $path = $_SERVER['REQUEST_URI'];
    if (strpos($path, '/assets/universal') === 0) {
      $response->headers->remove('X-Frame-Options');
      $response->headers->set('Access-Control-Allow-Origin', '*');
      $response->headers->set('Access-Control-Allow-Methods', 'GET');
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = array('updateResponseHeaders', -10);
    return $events;
  }

}
