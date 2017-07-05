<?php

namespace Drupal\ocms_unpublish\Plugin\views\field;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use DateTime;

/**
 * Field handler to for date range until expiration.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("scheduled_expiration_range")
 */
class ScheduledExpirationRange extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $node = $values->_entity;

    // Unpublished scheduled_update entity.
    $unpublished_tid = $node->schedule_unpublished_date->target_id;
    if ($unpublished_tid) {
      $schedule_unpublished_entity = \Drupal::entityTypeManager()
        ->getStorage('scheduled_update')
        ->load($unpublished_tid);

      $unpublished_datetime = DrupalDateTime::createFromTimestamp($schedule_unpublished_entity->update_timestamp->value);
      $now = new DateTime();
      $diff = $unpublished_datetime->diff($now);

      if ($diff->days > 90) {
        $diff_range = '> 90';
      }
      elseif ($diff->days > 60) {
        $diff_range = '90';
      }
      elseif ($diff->days > 30) {
        $diff_range = '60';
      }
      elseif ($diff->days > 15) {
        $diff_range = '30';
      }
      elseif ($diff->days > 5) {
        $diff_range = '15';
      }
      elseif ($diff->days > 1) {
        $diff_range = '5';
      }
      else {
        $diff_range = '01';
      }

      return $this->t('@expire_group', array('@expire_group' => $diff_range));
    }
    else {
      return $this->t('No scheduled unpublish date');
    }
  }

}
