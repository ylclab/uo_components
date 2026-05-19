<?php

namespace Drupal\uo_components\Plugin\Field\FieldFormatter;

use Drupal;
use Drupal\Core\Field\Attribute\FieldFormatter;
use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\uo_components\Entity\UoComponent;
use Exception;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\RendererInterface;

#[FieldFormatter(
    id: 'uo_components_formatter',
    label: new TranslatableMarkup('UO Components Inline Editor'),
    description: new TranslatableMarkup('Displays the referenced UO Components with an inline editor.'),
    field_types: [
        'entity_reference',
        'entity_reference_revisions',
    ],
)]
class UoComponentReferenceFormatter extends EntityReferenceFormatterBase implements ContainerFactoryPluginInterface
{
    protected EntityTypeManagerInterface $entityTypeManager;
    protected EntityDisplayRepositoryInterface $entityDisplayRepository;
    protected RendererInterface $renderer;

    public function __construct(
        $plugin_id,
        $plugin_definition,
        FieldDefinitionInterface $field_definition,
        array $settings,
        $label,
        $view_mode,
        array $third_party_settings,
        EntityTypeManagerInterface $entity_type_manager,
        EntityDisplayRepositoryInterface $entity_display_repository,
        RendererInterface $renderer,
    ) {
        parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
        $this->entityTypeManager = $entity_type_manager;
        $this->entityDisplayRepository = $entity_display_repository;
        $this->renderer = $renderer;
    }

    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): FormatterBase|UoComponentReferenceFormatter|static
    {
        $entity_type_manager = $container->get('entity_type.manager');
        if (!($entity_type_manager instanceof EntityTypeManagerInterface)) {
            throw new RuntimeException('The Entity Type Manager service is not available.');
        }

        $entity_display_repository = $container->get('entity_display.repository');
        if (!($entity_display_repository instanceof EntityDisplayRepositoryInterface)) {
            throw new RuntimeException('The Entity Display Repository service is not available.');
        }

        $renderer = $container->get('renderer');
        if (!($renderer instanceof RendererInterface)) {
            throw new RuntimeException('The Renderer service is not available.');
        }

        return new static(
            $plugin_id,
            $plugin_definition,
            $configuration['field_definition'],
            $configuration['settings'],
            $configuration['label'],
            $configuration['view_mode'],
            $configuration['third_party_settings'],
            $entity_type_manager,
            $entity_display_repository,
            $renderer
        );
    }

    public function viewElements(FieldItemListInterface $items, $langcode): array
    {
        $elements = [];

        $components = ['#markup' => ''];
        if ($items instanceof EntityReferenceFieldItemListInterface && count($items) > 0) {
            $components = [];
            foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
                if ($entity instanceof UoComponent) {
                    try {
                        $viewBuilder = $this->entityTypeManager->getViewBuilder('uo_component');
                        $components[$delta] = $viewBuilder->view($entity);
                    }
                    catch (Exception) {
                        Drupal::logger('uo_component')->warning('Unable to view @component', ['@component' => $entity]);
                        continue;
                    }
                }
            }
        }

        if (Drupal::currentUser()->hasPermission('access UO components editor')) {
            $parent_entity = $items->getEntity();
            $base_url = Drupal::request()->getSchemeAndHttpHost();
            $unique_id = md5($parent_entity->getEntityTypeId() . $parent_entity->id() . $items->getName() . $this->viewMode);

            $elements[] = [
                '#type' => 'component',
                '#component' => 'uo_components:formatter',
                '#props' => [
                    'id' => $unique_id,
                ],
                '#slots' => [
                    'components' => $components,
                ],
                '#attached' => [
                    'drupalSettings' => [
                        'uo_components' => [
                            'baseUrl' => $base_url,
                            'instances' => [
                                $unique_id => [
                                    'entity_type' => $parent_entity->getEntityTypeId() . '--' . $parent_entity->bundle(),
                                    'entity_uuid' => $parent_entity->uuid(),
                                    'field_name' => $items->getName(),
                                    'view_mode' => $this->viewMode,
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        }
        else {
            $elements = $components;
        }

        return $elements;
    }

    public static function isApplicable(FieldDefinitionInterface $field_definition): bool
    {
        $target_type = $field_definition->getFieldStorageDefinition()->getSetting('target_type');

        return $target_type === 'uo_component';
    }
}
