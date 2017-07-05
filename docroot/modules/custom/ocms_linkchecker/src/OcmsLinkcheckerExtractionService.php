<?php

namespace Drupal\ocms_linkchecker;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Implements the PupLinkcheckerExtractionInterface.
 */
class PupLinkcheckerExtractionService implements PupLinkcheckerExtractionInterface {

  /**
   * OCMS Linkchecker Parsing Service.
   *
   * @var \Drupal\ocms_linkchecker\PupLinkcheckerParsingInterface
   */
  protected $parsingService;

  /**
   * OCMS Linkchecker Utility Service.
   *
   * @var \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface
   */
  protected $utilityService;

  /**
   * Drupal's language manager service.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface;
   */
  protected $languageManager;

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs the OCMS Linkchecker Extraction Service object.
   *
   * @param \Drupal\ocms_linkchecker\PupLinkcheckerParsingInterface $ocms_linkchecker_parsing
   *   The OCMS Linkchecker Parsing Service.
   * @param \Drupal\ocms_linkchecker\PupLinkcheckerUtilityInterface $ocms_linkchecker_utility
   *   The OCMS Linkchecker Utility Service.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(PupLinkcheckerParsingInterface $ocms_linkchecker_parsing, PupLinkcheckerUtilityInterface $ocms_linkchecker_utility, LanguageManagerInterface $language_manager, RequestStack $request_stack, ConfigFactoryInterface $config_factory) {
    $this->parsingService = $ocms_linkchecker_parsing;
    $this->utilityService = $ocms_linkchecker_utility;
    $this->languageManager = $language_manager;
    $this->request = $request_stack->getCurrentRequest();
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function extractContentLinks($text, $contentPath) {
    global $base_root;
    $isHttps = $this->request->isSecure();

    $config = $this->configFactory->get('ocms_linkchecker.settings');

    $htmlDom = Html::load($text);
    $urls = array();

    // Finds all hyperlinks in the content.
    if ($config->get('extract.from_a') == 1) {
      $links = $htmlDom->getElementsByTagName('a');
      foreach ($links as $link) {
        $urls[] = $link->getAttribute('href');
      }

      $links = $htmlDom->getElementsByTagName('area');
      foreach ($links as $link) {
        $urls[] = $link->getAttribute('href');
      }
    }

    // Finds all audio links in the content.
    if ($config->get('extract.from_audio') == 1) {
      $audios = $htmlDom->getElementsByTagName('audio');
      foreach ($audios as $audio) {
        $urls[] = $audio->getAttribute('src');

        // Finds source tags with links in the audio tag.
        $sources = $audio->getElementsByTagName('source');
        foreach ($sources as $source) {
          $urls[] = $source->getAttribute('src');
        }
        // Finds track tags with links in the audio tag.
        $tracks = $audio->getElementsByTagName('track');
        foreach ($tracks as $track) {
          $urls[] = $track->getAttribute('src');
        }
      }
    }

    // Finds embed tags with links in the content.
    if ($config->get('extract.from_embed') == 1) {
      $embeds = $htmlDom->getElementsByTagName('embed');
      foreach ($embeds as $embed) {
        $urls[] = $embed->getAttribute('src');
        $urls[] = $embed->getAttribute('pluginurl');
        $urls[] = $embed->getAttribute('pluginspage');
      }
    }

    // Finds iframe tags with links in the content.
    if ($config->get('extract.from_iframe') == 1) {
      $iframes = $htmlDom->getElementsByTagName('iframe');
      foreach ($iframes as $iframe) {
        $urls[] = $iframe->getAttribute('src');
      }
    }

    // Finds img tags with links in the content.
    if ($config->get('extract.from_img') == 1) {
      $imgs = $htmlDom->getElementsByTagName('img');
      foreach ($imgs as $img) {
        $urls[] = $img->getAttribute('src');
        $urls[] = $img->getAttribute('longdesc');
      }
    }

    // Finds object/param tags with links in the content.
    if ($config->get('extract.from_object') == 1) {
      $objects = $htmlDom->getElementsByTagName('object');
      foreach ($objects as $object) {
        $urls[] = $object->getAttribute('data');
        $urls[] = $object->getAttribute('codebase');

        // Finds param tags with links in the object tag.
        $params = $object->getElementsByTagName('param');
        foreach ($params as $param) {
          // @todo
          // - Try to extract links in unkown "flashvars" values
          //   (e.g., file=http://, data=http://).
          $names = array('archive', 'filename', 'href', 'movie', 'src', 'url');
          if ($param->hasAttribute('name') && in_array($param->getAttribute('name'), $names)) {
            $urls[] = $param->getAttribute('value');
          }

          $srcs = array('movie');
          if ($param->hasAttribute('src') && in_array($param->getAttribute('src'), $srcs)) {
            $urls[] = $param->getAttribute('value');
          }
        }
      }
    }

    // Finds video tags with links in the content.
    if ($config->get('extract.from_video') == 1) {
      $videos = $htmlDom->getElementsByTagName('video');
      foreach ($videos as $video) {
        $urls[] = $video->getAttribute('poster');
        $urls[] = $video->getAttribute('src');

        // Finds source tags with links in the video tag.
        $sources = $video->getElementsByTagName('source');
        foreach ($sources as $source) {
          $urls[] = $source->getAttribute('src');
        }
        // Finds track tags with links in the audio tag.
        $tracks = $video->getElementsByTagName('track');
        foreach ($tracks as $track) {
          $urls[] = $track->getAttribute('src');
        }
      }
    }

    // Remove empty values.
    $urls = array_filter($urls);
    // Remove duplicate urls.
    $urls = array_unique($urls);

    // What type of links should be checked?
    $linkcheckerCheckLinkTypes = $config->get('general.check_link_types');

    $links = array();
    foreach ($urls as $url) {
      // Decode HTML links into plain text links.
      // DOMDocument->loadHTML does not provide the RAW url from code. All html
      // entities are already decoded.
      // @todo: Try to find a way to get the raw value.
      $urlDecoded = $url;

      // Prefix protocol relative urls with a protocol to allow link checking.
      if (preg_match('!^//!', $urlDecoded)) {
        $httpProtocol = $isHttps ? 'https' : 'http';
        $urlDecoded = $httpProtocol . ':' . $urlDecoded;
      }

      // FIXME: #1149596 HACK - Encode spaces in URLs, so validation equals TRUE and link gets added.
      $urlEncoded = str_replace(' ', '%20', $urlDecoded);

      // Full qualified URLs.
      if ($linkcheckerCheckLinkTypes != 2 && UrlHelper::isValid($urlEncoded, TRUE)) {
        // Add to Array and change HTML links into plain text links.
        $links[$urlDecoded][] = $url;
      }
      // Skip mailto:, javascript:, etc.
      elseif (preg_match('/^\w[\w.+]*:/', $urlDecoded)) {
        continue;
      }
      // Local URLs. $linkchecker_check_links_types = 0 or 2
      elseif ($linkcheckerCheckLinkTypes != 1 && UrlHelper::isValid($urlEncoded, FALSE)) {
        // Get full qualified url with base path of content.
        $absoluteContentPath = $this->parsingService->getAbsoluteUrl($contentPath);

        // Absolute local URLs need to start with [/].
        if (preg_match('!^/!', $urlDecoded)) {
          // Add to Array and change HTML encoded links into plain text links.
          $links[$base_root . $urlDecoded][] = $url;
        }
        // Anchors and URL parameters like "#foo" and "?foo=bar".
        elseif (!empty($contentPath) && preg_match('!^[?#]!', $urlDecoded)) {
          // Add to Array and change HTML encoded links into plain text links.
          $links[$contentPath . $urlDecoded][] = $url;
        }
        // Relative URLs like "./foo/bar" and "../foo/bar".
        elseif (!empty($absoluteContentPath) && preg_match('!^\.{1,2}/!', $urlDecoded)) {
          // Build the URI without hostname before the URI is normalized and
          // dot-segments will be removed. The hostname is added back after the
          // normalization has completed to prevent hostname removal by the regex.
          // This logic intentionally does not implement all the rules definied in
          // RFC 3986, section 5.2.4 to show broken links and over-dot-segmented
          // URIs; e.g., http://example.com/../../foo/bar.
          // For more information, see http://drupal.org/node/832388.
          $path = substr_replace($absoluteContentPath . $urlDecoded, '', 0, strlen($base_root));

          // Remove './' segments where possible.
          $path = str_replace('/./', '/', $path);

          // Remove '../' segments where possible. Loop until all segments are
          // removed. Taken over from _drupal_build_css_path() in common.inc.
          $last = '';
          while ($path != $last) {
            $last = $path;
            $path = preg_replace('`(^|/)(?!\.\./)([^/]+)/\.\./`', '$1', $path);
          }

          // Glue the hostname and path to full-qualified URI.
          $links[$base_root . $path][] = $url;
        }
        // Relative URLs like "test.png".
        elseif (!empty($absoluteContentPath) && preg_match('!^[^/]!', $urlDecoded)) {
          $links[$absoluteContentPath . $urlDecoded][] = $url;
        }
        else {
          // @todo Are there more special cases the module need to handle?
        }
      }
    }

    return $links;
  }

  /**
   * {@inheritdoc}
   */
  public function extractNodeLinks($node, $returnFieldNames = FALSE) {
    $filter = new \stdClass();
    $filter->settings['filter_url_length'] = 72;

    // Create array of node fields to scan.
    $textItems = array();
    $textItemsByField = array();

    // Add fields typically not used for urls to the bottom. This way a link may
    // found earlier while looping over $textItemsByField below.
    $textItemsByField = array_merge($textItemsByField, $this->parsingService->parseFields('node', $node->bundle(), $node, TRUE));
    // @see: https://api.drupal.org/api/drupal/core%21modules%21filter%21filter.module/function/_filter_url/8.3.x
    $textItemsByField['title'][] = _filter_url($node->getTitle(), $filter);
    $textItems = $this->utilityService->recurseArrayValues($textItemsByField);

    // Get the absolute node path for extraction of relative links.
    if ($node->language()) {
      $path = $node->toUrl('canonical', ['absolute' => true, 'language' => $node->language()])->toString();
    }
    else {
      $path = $node->toUrl('canonical', ['absolute' => true])->toString();
    }

    // Extract all links in a node.
    $links = $this->extractContentLinks(implode(' ', $textItems), $path);

    // Return either the array of links, or an array of field names containing
    // each link, depending on what was requested.
    if (!$returnFieldNames) {
      return $links;
    }
    else {
      $fieldNames = array();
      foreach ($textItemsByField as $fieldName => $items) {
        foreach ($items as $item) {
          foreach ($links as $uri => $link) {
            // We only need to do a quick check here to see if the URL appears
            // anywhere in the text; if so, that means users with access to this
            // field will be able to see the URL (and any private data such as
            // passwords contained in it). This is sufficient for the purposes of
            // _linkchecker_link_node_ids(), where this information is used.
            foreach ($link as $originalLink) {
              if (strpos($item, $originalLink) !== FALSE) {
                $fieldNames[$uri][$fieldName] = $fieldName;
              }
              // URLs in $links have been auto-decoded by DOMDocument->loadHTML
              // and does not provide the RAW url with html special chars.
              // NOTE: htmlspecialchars() is 30% slower than str_replace().
              elseif (strpos($item, str_replace('&', '&amp;', $originalLink)) !== FALSE) {
                $fieldNames[$uri][$fieldName] = $fieldName;
              }
            }
          }
        }
      }

      return $fieldNames;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function extractCommentLinks($comment, $returnFieldNames = FALSE) {
  }

  /**
   * {@inheritdoc}
   */
  public function extractBlockLinks($block, $returnFieldNames = FALSE) {
  }
}
