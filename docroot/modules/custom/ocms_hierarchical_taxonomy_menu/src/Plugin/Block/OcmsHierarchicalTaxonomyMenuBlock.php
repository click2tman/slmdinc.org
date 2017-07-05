<?php

namespace Drupal\ocms_hierarchical_taxonomy_menu\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Template\Attribute;
use Drupal\field\Entity\FieldConfig;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'PupHierarchicalTaxonomyMenuBlock' block.
 *
 * @Block(
 *  id = "ocms_hierarchical_taxonomy_menu",
 *  admin_label = @Translation("OCMS Hierarchical Taxonomy Menu"),
 *  category = @Translation("OCMS Custom"),
 *   context = {
 *     "node" = @ContextDefinition(
 *       "entity:node",
 *       label = @Translation("Current Node")
 *     )
 *   }
 * )
 */
class PupHierarchicalTaxonomyMenuBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  protected $entityFieldManager;

  /**
   * The term storage handler.
   *
   * @var \Drupal\taxonomy\TermStorageInterface
   */
  protected $storageController;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * Constructs a PupHierarchicalTaxonomyMenuBlock object.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager service
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityFieldManagerInterface $entity_field_manager,
    EntityTypeManagerInterface $entity_type_manager,
    LanguageManagerInterface $language_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityFieldManager = $entity_field_manager;
    $this->storageController = $entity_type_manager->getStorage('taxonomy_term');
    $this->languageManager = $language_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_field.manager'),
      $container->get('entity_type.manager'),
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'vocabulary' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['vocabulary'] = [
      '#title' => $this->t('Vocabulary'),
      '#type' => 'select',
      '#options' => $this->getVocabularyOptions(),
      '#required' => TRUE,
      '#default_value' => $this->configuration['vocabulary'],
    ];
    return $form;
  }

  /**
   * Generate vocabulary select options.
   */
  private function getVocabularyOptions() {
    $options = [];
    $vocabularies = taxonomy_vocabulary_get_names();
    foreach ($vocabularies as $vocabulary) {
      $options[$vocabulary] = $vocabulary;
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['vocabulary'] = $form_state->getValue('vocabulary');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = $this->getContextValue('node');
    $nodeReferencesConfiguredVocabulary = false;
    $fields = $this->entityFieldManager->getFieldDefinitions('node', $node->getType());
    foreach ($fields as $field) {
      if (($field instanceof FieldConfig) && method_exists($field, 'getSettings')) {
        $settings = $field->getSettings();
        if (isset($settings['handler_settings']['target_bundles']) && in_array($this->configuration['vocabulary'], $settings['handler_settings']['target_bundles'])) {
          $nodeReferencesConfiguredVocabulary = true;
          $nodeVocabularyReferenceField = $field->getName();
          continue;
        }
      }
    }
    if ($nodeReferencesConfiguredVocabulary) {
      $language = $this->languageManager->getCurrentLanguage()->getId();
      $nodeReferencedTid = $node->get($nodeVocabularyReferenceField)->getValue()[0]['target_id'];

      // Load the parents of the current term
      // Note: This also loads the current term itself
      $parentsObjects = $this->storageController->loadAllParents($nodeReferencedTid);
      $parentsIds = [];
      foreach ($parentsObjects as $parentObject) {
        $parentsIds[] = $parentObject->id();
      }

      // The root term is Level 1
      $levels = count($parentsIds);
      if ($levels >= 2) {
        $activeTrailIndex = $levels - 2;
        $activeTrailId = $parentsIds[$activeTrailIndex];
	$activeParentId = $parentsIds[1];
      }
      else {
        $activeTrailId = 0;
	$activeParentId = 0;
      }
      $vocabularyConfig = $this->configuration['vocabulary'];
      $vocabularyConfig = explode('|', $vocabularyConfig);
      $vocabulary = isset($vocabularyConfig[0]) ? $vocabularyConfig[0] : NULL;
      $vocabularyTree = $this->storageController->loadTree($vocabulary, 0, NULL, FALSE);

      $listItems = [];
      foreach ($vocabularyTree as $item) {
        $includeItem = false;
        $parentId = 0;
	if (in_array($item->tid, $parentsIds)) {
          // This is a parent that we want to show
          $includeItem = true;
          $level = $levels - array_search($item->tid, $parentsIds);
          $itemsParentsObjects = $this->storageController->loadParents($item->tid);
          foreach ($itemsParentsObjects as $parentObject) {
            if (in_array($parentObject->id(), $parentsIds) && ($parentObject->id() != $nodeReferencedTid)) {
              $parentId = $parentObject->id();
              break;
            }
          }
        }
        else {
          $itemsParentsObjects = $this->storageController->loadParents($item->tid);
          foreach ($itemsParentsObjects as $parentObject) {
            if (in_array($parentObject->id(), $parentsIds)) {
              // This is a child of a parent that we are showing
	      // Note: This results in showing the children of the active term,
	      //       which is exactly as the client requested. 
              $includeItem = true;
              $level = $levels - array_search($parentObject->id(), $parentsIds) + 1;
              $parentId = $parentObject->id();
              break;
            }
          }
        }
        if ($includeItem) {
          if ($item->tid == $nodeReferencedTid) {
            $class = [
              'level-' . $level,
              'is-active',
            ];
	  }
          else {
            $class = [
              'level-' . $level,
            ];
	  }
          if ($item->tid == $activeTrailId) {
            $class[] = 'is-active-trail';
	  }
          if ($item->tid == $activeParentId) {
            $class[] = 'is-active-parent';
	  }
          $term = Term::load($item->tid);
          $translationLanguages = $term->getTranslationLanguages();
          if (isset($translationLanguages[$language])) {
            $itemTranslated = $term->getTranslation($language);
          }
          else {
            $itemTranslated = $term;
          }
	  $options = [
            'title' => $itemTranslated->label(),
            'id' => $this->t('term-:termId', array(':termId' => $itemTranslated->id())),
            'rel' => $parentId ? 'child' : 'parent',
          ];
	  $listItems[] = [
            'title' => $itemTranslated->label(),
            'id' => $itemTranslated->id(),
            'url' => $itemTranslated->toUrl(),
            'options' => $options,
            'attributes' => new Attribute([
              'class' => $class,
              'data-indent-level' => $level,
            ]),
            'parentId' => $parentId,
	    'below' => [],
          ];
        }
      }

      $items = [];
      // The items *should* already be in an order such that a child will never
      // appear before the parent has already been loaded. Just to make this
      // more robust we will use a while loop and unset elements as they are
      // placed in the hierarchy found.
      while ($listItems) {
        foreach ($listItems as $key => $item) {
          if ($item['parentId']) {
            $found = $this->setItemBelow($items, $item);
          }
          else {
            $items[$item['id']] = $item;
            $found = true;
          }
          if ($found) {
            unset($listItems[$key]);
	  }
	}
      }

      // There should be only one root key.
      $rootKey = array_keys($items);

      // We never show Level 1 (root) items in the secondary navigation.
      if (isset($items[$rootKey[0]]['below'])) {
        $items = $items[$rootKey[0]]['below'];
      }
      else {
        $items = [];
      }
      if ($items) {
        return [
          '#theme' => 'ocms_hierarchical_taxonomy_menu',
          '#items' => $items,
        ];
      }
    }
  }

  /**
   * Places an item in its parent's below attribute
   * 
   * @param array $items
   *   The master item tree
   * @param object $item
   *   The item being processed
   */
  private function setItemBelow(&$items, $item) {
    if (isset($items[$item['parentId']])) {
      $items[$item['parentId']]['below'][$item['id']] = $item;
      return true;
    }
    else {
      foreach ($items as $itemId => $itemArray) {
        $found = $this->setItemBelow($itemArray['below'], $item);
        if ($found) {
          $items[$itemId]['below'] = $itemArray['below'];
          return true;
	}
      }
    }
  }

}
