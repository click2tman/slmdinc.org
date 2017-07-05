<?php

/**
 * @file
 * Provide views data for ocms_linkchecker.module.
 */

/**
 * Implements hook_views_data().
 */
function ocms_linkchecker_views_data() {
  $data = [];
  $data['ocms_linkchecker_link']['table']['group'] = t('OCMS Linkchecker Link Table');
  $data['ocms_linkchecker_link']['table']['base'] = [
    'field' => 'lid',
    'title' => t('OCMS Linkchecker Link Table'),
    'help' => t('The Link table contains all the information for each link that will be checked'),
  ];

  $data['ocms_linkchecker_link']['lid'] = [
    'title' => t('Link ID'),
    'help' => t('A unique ID that is incremented automatically when a row is created'),
    'field' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['ocms_linkchecker_link']['url'] = [
    'title' => t('URL'),
    'help' => t('The fully qualified URL to check'),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['ocms_linkchecker_link']['method'] = [
    'title' => t('Method'),
    'help' => t('The method to check links (HEAD, GET)'),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['ocms_linkchecker_link']['code'] = [
    'title' => t('Code'),
    'help' => t('The HTTP status code returned when checking the link'),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['ocms_linkchecker_link']['error'] = [
    'title' => t('Error'),
    'help' => t('The message returned from the server when checking the link'),
    'field' => [
      'id' => 'standard',
    ],
  ];

  $data['ocms_linkchecker_link']['fail_count'] = [
    'title' => t('Fail Count'),
    'help' => t('The number of times this link has had a failure response'),
    'field' => [
      'id' => 'numeric',
    ],
  ];

  $data['ocms_linkchecker_link']['last_checked'] = [
    'title' => t('Last Checked'),
    'help' => t('A timestamp of when the link was last checked'),
    'field' => [
      'id' => 'date',
    ],
    'filter' => [
      'id' => 'date',
    ],
    'sort' => [
      'id' => 'date',
    ],
  ];

  $data['ocms_linkchecker_link']['status'] = [
    'title' => t('Status'),
    'help' => t('A boolean indicating whether this link should be checked'),
    'field' => [
      'id' => 'boolean',
    ],
  ];

  $data['ocms_linkchecker_entity']['table']['group'] = t('OCMS Linkchecker Entity Table');
  $data['ocms_linkchecker_entity']['table']['base'] = [
    'field' => 'entity_id',
    'title' => t('OCMS Linkchecker Entity Table'),
    'help' => t('The Entity table contains the entity IDs that reference given link IDs'),
  ];

  $data['ocms_linkchecker_entity']['entity_id'] = [
    'title' => t('ID'),
    'help' => t('The ID of the entity referencing a link'),
    'field' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'relationship' => [
      'base' => 'node_field_data',
      'base field' => 'nid',
      'id' => 'standard',
      'help' => t('Node being referenced'),
      'title' => t('Node'),
    ],
  ];

  $data['ocms_linkchecker_entity']['entity_type'] = [
    'title' => t('Entity Type'),
    'help' => t('The entity type that is referencing a link'),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['ocms_linkchecker_entity']['bundle'] = [
    'title' => t('Bundle'),
    'help' => t('The bundle that is referencing a link'),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'bundle',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['ocms_linkchecker_entity']['langcode'] = [
    'title' => t('Language Code'),
    'help' => t('The language code of the entity that is referencing a link'),
    'field' => [
      'id' => 'language',
    ],
    'filter' => [
      'id' => 'language',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['ocms_linkchecker_entity']['lid'] = [
    'title' => t('Link ID'),
    'help' => t('The link ID of the link being referenced'),
    'field' => [
      'id' => 'numeric',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'relationship' => [
      'base' => 'ocms_linkchecker_link',
      'base field' => 'lid',
      'id' => 'standard',
      'help' => t('Link referenced by this node'),
      'title' => t('Link'),
    ],
  ];

  return $data;
}
