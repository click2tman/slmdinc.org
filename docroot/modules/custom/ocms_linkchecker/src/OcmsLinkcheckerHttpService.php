<?php

namespace Drupal\ocms_linkchecker;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Exception\TransferException;

/**
 * Implements the PupLinkcheckerHttpInterface.
 */
class PupLinkcheckerHttpService implements PupLinkcheckerHttpInterface {

  /**
   * Drupal's database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The HTTP client with which to test the links.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs the OCMS Linkchecker Http Service object.
   *
   * @param \Drupal\Core\Database\Connection
   *   The database connection
   * @param \GuzzleHttp\ClientInterface $http_client
   *   A Guzzle client object.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(Connection $database, ClientInterface $http_client, ConfigFactoryInterface $config_factory) {
    $this->connection = $database;
    $this->httpClient = $http_client;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function checkLink($link) {
    $url = $link->url;
    try {
      if ($link->method == 'GET') {
        $response = $this->httpClient
          ->get($url);
      }
      else {
        $response = $this->httpClient
          ->head($url);
      }
    }
    catch (BadResponseException $exception) {
      $response = $exception->getResponse();
      $code = '400';
      $error = 'There was a bad response exception caught.';
      \Drupal::logger('ocms_linkchecker')->notice('A bad response exception was caught for @url.', array('@url' => $url));
      watchdog_exception('ocms_linkchecker', $exception);
    }
    catch (ClientException $exception) {
      $response = $exception->getResponse();
      $code = '400';
      $error = 'There was a client exception caught.';
      \Drupal::logger('ocms_linkchecker')->notice('A client exception was caught for @url.', array('@url' => $url));
      watchdog_exception('ocms_linkchecker', $exception);
    }
    catch (ConnectException $exception) {
      $response = $exception->getResponse();
      $code = '400';
      $error = 'There was a connection exception caught.';
      \Drupal::logger('ocms_linkchecker')->notice('A connection exception was caught for @url.', array('@url' => $url));
      watchdog_exception('ocms_linkchecker', $exception);
    }
    catch (RequestException $exception) {
      $response = $exception->getResponse();
      $code = '400';
      $error = 'There was a request exception caught.';
      \Drupal::logger('ocms_linkchecker')->notice('A request exception was caught for @url.', array('@url' => $url));
      watchdog_exception('ocms_linkchecker', $exception);
    }
    catch (ServerException $exception) {
      $response = $exception->getResponse();
      $code = '400';
      $error = 'There was a server exception caught.';
      \Drupal::logger('ocms_linkchecker')->notice('A server exception was caught for @url.', array('@url' => $url));
      watchdog_exception('ocms_linkchecker', $exception);
    }
    catch (TooManyRedirectsException $exception) {
      $response = $exception->getResponse();
      $code = '400';
      $error = 'There were too many redirects.';
      \Drupal::logger('ocms_linkchecker')->notice('There were too many redirects for @url.', array('@url' => $url));
      watchdog_exception('ocms_linkchecker', $exception);
    }
    catch (\Exception $exception) {
      $response = $exception->getResponse();
      $code = '400';
      $error = 'There was an unexpected exception caught.';
      \Drupal::logger('ocms_linkchecker')->notice('An unexpected exception was caught for @url.', array('@url' => $url));
      watchdog_exception('ocms_linkchecker', $exception);
    }

    if (!empty($response)) {
      $code = $response->getStatusCode();
      $error = $response->getReasonPhrase();
    }

    $config = $this->configFactory->get('ocms_linkchecker.settings');
    $ignoreResponseCodes = preg_split('/(\r\n?|\n)/', $config->get('error.ignore_response_codes'));
    if (in_array($code, $ignoreResponseCodes)) {
      $failIncrement = -1 * $link->fail_count;
    }
    else {
      $failIncrement = 1;
    }
    $this->connection->update('ocms_linkchecker_link')
      ->condition('lid', $link->lid)
      ->fields(array(
        'code' => $code,
        'error' => $error,
        'fail_count' => 0,
        'last_checked' => time(),
      ))
      ->expression('fail_count',
        'fail_count + :failIncrement',
        array(':failIncrement' => $failIncrement))
      ->execute();
  }
}
