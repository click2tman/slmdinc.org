<?php

namespace Drupal\ocms_linkchecker;

use Drupal\Core\Logger\RfcLogLevel;

/**
 * Defines an interface for the Link logging services.
 */
interface PupLinkcheckerLoggingInterface {

  /**
   * Logs a system message based on configured security level.
   *
   * @param $type
   *   The category to which this message belongs. Can be any string, but the
   *   general practice is to use the name of the module calling watchdog().
   * @param $message
   *   The message to store in the log. Keep $message translatable
   *   by not concatenating dynamic values into it! Variables in the
   *   message should be added by using placeholder strings alongside
   *   the variables argument to declare the value of the placeholders.
   *   See t() for documentation on how $message and $variables interact.
   * @param $variables
   *   Array of variables to replace in the message on display or
   *   NULL if message is already translated or not possible to
   *   translate.
   * @param $severity
   *   The severity of the message; one of the following values as defined in
   *   @link http://www.faqs.org/rfcs/rfc3164.html RFC 3164: @endlink
   *   - RfcLogLevel::EMERGENCY: Emergency, system is unusable.
   *   - RfcLogLevel::ALERT: Alert, action must be taken immediately.
   *   - RfcLogLevel::CRITICAL: Critical conditions.
   *   - RfcLogLevel::ERROR: Error conditions.
   *   - RfcLogLevel::WARNING: Warning conditions.
   *   - RfcLogLevel::NOTICE: (default) Normal but significant conditions.
   *   - RfcLogLevel::INFO: Informational messages.
   *   - RfcLogLevel::DEBUG: Debug-level messages.
   * @param $link
   *   A link to associate with the message.
   *
   * @see watchdog_severity_levels()
   * @see watchdog()
   */
  public function logMessage($type, $message, $variables = array(), $severity = RfcLogLevel::NOTICE, $link = NULL);
}
