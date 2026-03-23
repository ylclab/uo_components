<?php

namespace Drupal\uo_components\Entity;

use Drupal;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Exception\UnsupportedEntityTypeDefinitionException;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\user\EntityOwnerInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * Defines the Component entity.
 *
 * @ingroup uo_components
 *
 * @ContentEntityType(
 *   id = "uo_component",
 *   label = @Translation("UO Component"),
 *   label_collection = @Translation("UO Components"),
 *   label_singular = @Translation("UO component"),
 *   label_plural = @Translation("UO components"),
 *   label_count = @PluralTranslation(
 *     singular = "@count UO component",
 *     plural = "@count UO components",
 *   ),
 *   bundle_label = @Translation("UO Component type"),
 *   handlers = {
 *     "view_builder" = "Drupal\uo_components\Entity\UoComponentViewBuilder",
 *     "list_builder" = "Drupal\Core\Entity\EntityListBuilder",
 *     "translation" = "Drupal\content_translation\ContentTranslationHandler",
 *     "access" = "Drupal\uo_components\Access\UoComponentAccessControlHandler",
 *   },
 *   base_table = "uo_component",
 *   data_table = "uo_component_field_data",
 *   revision_table = "uo_component_revision",
 *   revision_data_table = "uo_component_field_revision",
 *   translatable = TRUE,
 *   revisionable = TRUE,
 *   admin_permission = "administer UO component types",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "uid",
 *     "owner" = "uid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log",
 *   },
 *   bundle_entity_type = "uo_component_type",
 *   common_reference_target = TRUE,
 *   permission_granularity = "bundle",
 *   links = {
 *     "canonical" = "/uo_component/{uo_component}",
 *     "version-history" = "/uo_component/{uo_component}/revisions",
 *     "revision" = "/uo_component/{uo_component}/revisions/{uo_component_revision}/view",
 *     "revision_revert" = "/uo_component/{uo_component}/revisions/{uo_component_revision}/revert",
 *     "revision_delete" = "/uo_component/{uo_component}/revisions/{uo_component_revision}/delete",
 *     "translation_revert" = "/uo_component/{uo_component}/revisions/{uo_component_revision}/revert/{langcode}",
 *     "collection" = "/admin/content/uo_components",
 *   },
 * )
 */
class UoComponent extends RevisionableContentEntityBase implements ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface, RevisionLogInterface
{
    use EntityChangedTrait;
    use EntityPublishedTrait;
    use EntityOwnerTrait;

    /**
     * {@inheritdoc}
     */
    public static function preCreate(EntityStorageInterface $storage, array &$values): void
    {
        parent::preCreate($storage, $values);
        $values += [
            'uid' => Drupal::currentUser()->id(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function urlRouteParameters($rel): array
    {
        $uri_route_parameters = parent::urlRouteParameters($rel);

        if ($rel === 'revision_revert') {
            $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
        }
        elseif ($rel === 'revision_delete') {
            $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
        }

        return $uri_route_parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function preSave(EntityStorageInterface $storage): void
    {
        parent::preSave($storage);

        if (!$this->isNew()) {
            $this->setRevisionCreationTime(Drupal::time()->getRequestTime());
            $this->setRevisionUserId(Drupal::currentUser()->id());
        }
    }

    /**
     * {@inheritdoc}
     * @throws UnsupportedEntityTypeDefinitionException
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array
    {
        $fields = parent::baseFieldDefinitions($entity_type);
        $fields += static::publishedBaseFieldDefinitions($entity_type);
        $fields += static::ownerBaseFieldDefinitions($entity_type);

        $fields['name'] = BaseFieldDefinition::create('string')
            ->setLabel(new TranslatableMarkup('Name'))
            ->setDescription(new TranslatableMarkup('The name of the component entity.'))
            ->setRevisionable(true)
            ->setSettings([
                'max_length' => 255,
                'text_processing' => 0,
            ])
            ->setDefaultValue('')
            ->setDisplayOptions('view', [
                'label' => 'hidden',
                'type' => 'string',
                'weight' => -10,
            ])
            ->setDisplayOptions('form', [
                'type' => 'string_textfield',
                'weight' => -10,
            ])
            ->setDisplayConfigurable('form', true)
            ->setDisplayConfigurable('view', true)
            ->setRequired(true);

        $fields['created'] = BaseFieldDefinition::create('created')
            ->setLabel(new TranslatableMarkup('Created'))
            ->setDescription(new TranslatableMarkup('The time that the entity was created.'))
            ->setRevisionable(true);

        $fields['changed'] = BaseFieldDefinition::create('changed')
            ->setLabel(new TranslatableMarkup('Changed'))
            ->setDescription(new TranslatableMarkup('The time that the entity was last edited.'))
            ->setRevisionable(true);

        $fields['status']
            ->setDisplayOptions('form', [
                'type' => 'boolean_checkbox',
                'weight' => 120,
            ])
            ->setDisplayConfigurable('form', true);

        $fields['uid']
            ->setLabel(new TranslatableMarkup('Authored by'))
            ->setDescription(new TranslatableMarkup('The user that created this component.'))
            ->setRevisionable(true)
            ->setDisplayOptions('view', [
                'label' => 'hidden',
                'type' => 'author',
                'weight' => 0,
            ])
            ->setDisplayOptions('form', [
                'type' => 'entity_reference_autocomplete',
                'weight' => 5,
                'settings' => [
                    'match_operator' => 'CONTAINS',
                    'size' => '60',
                    'autocomplete_type' => 'tags',
                    'placeholder' => '',
                ],
            ])
            ->setDisplayConfigurable('form', true)
            ->setDisplayConfigurable('view', true);

        if ($entity_type->hasKey('revision')) {
            $fields['revision_log_message'] = BaseFieldDefinition::create('string_long')
                ->setLabel(new TranslatableMarkup('Revision log message'))
                ->setDescription(new TranslatableMarkup('Briefly describe the changes you have made.'))
                ->setRevisionable(true)
                ->setDefaultValue('')
                ->setDisplayOptions('form', [
                    'type' => 'string_textarea',
                    'weight' => 25,
                    'settings' => [
                        'rows' => 4,
                    ],
                ]);
        }

        return $fields;
    }

    public function getName()
    {
        return $this->get('name')->value;
    }

    public function setName($name): static
    {
        $this->set('name', $name);

        return $this;
    }

    public function getCreatedTime()
    {
        return $this->get('created')->value;
    }

    public function setCreatedTime($timestamp): static
    {
        $this->set('created', $timestamp);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function label()
    {
        return $this->getName();
    }
}
