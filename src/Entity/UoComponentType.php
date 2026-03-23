<?php

namespace Drupal\uo_components\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityDescriptionInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Defines the Component type entity.
 *
 * @ConfigEntityType(
 *   id = "uo_component_type",
 *   label = @Translation("UO Component type"),
 *   label_collection = @Translation("UO Component types"),
 *   label_singular = @Translation("UO component type"),
 *   label_plural = @Translation("UO component types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count UO component type",
 *     plural = "@count UO component types",
 *   ),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\Core\Entity\EntityListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "uo_component_type",
 *   admin_permission = "administer UO component types",
 *   bundle_of = "uo_component",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/uo-component-types/{component_type}",
 *     "collection" = "/admin/structure/uo-component-types",
 *   },
 * )
 */
class UoComponentType extends ConfigEntityBundleBase implements ConfigEntityInterface, EntityDescriptionInterface {

    use StringTranslationTrait;

    protected string $id;
    protected string $label;
    protected string $description;

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description): EntityDescriptionInterface|UoComponentType|static
    {
        $this->description = $description;
        return $this;
    }
}
