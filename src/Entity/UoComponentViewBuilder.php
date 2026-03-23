<?php

namespace Drupal\uo_components\Entity;

use Drupal;
use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Exception;

class UoComponentViewBuilder extends EntityViewBuilder
{
    public function buildComponents(array &$build, array $entities, array $displays, $view_mode): void
    {
        try {
            foreach ($entities as $id => $entity) {
                if ($entity instanceof UoComponent) {
                    $render_array = $this->getRenderArrayForComponent($entity);
                    if (!empty($render_array)) {
                        $build[$id]['content'] = $render_array;
                    }
                }
            }
        }
        catch (Exception $e) {
            Drupal::logger('uo_components')->error('Error building UoComponent entities: @message', ['@message' => $e->getMessage()]);
        }
    }

    public function getRenderArrayForComponent(UoComponent $entity): array
    {
        return match ($entity->bundle()) {
            'button' => $this->getRenderArrayForButtonComponent($entity),
            'caption_photo' => $this->getRenderArrayForCaptionPhotoComponent($entity),
            'card' => $this->getRenderArrayForCardComponent($entity),
            'envelope' => $this->getRenderArrayForEnvelopeComponent($entity),
            'feature' => $this->getRenderArrayForFeatureComponent($entity),
            'gallery' => $this->getRenderArrayForGalleryComponent($entity),
            'grid' => $this->getRenderArrayForGridComponent($entity),
            'hero' => $this->getRenderArrayForHeroComponent($entity),
            'html' => $this->getRenderArrayForHtmlComponent($entity),
            'photo_button' => $this->getRenderArrayForPhotoButtonComponent($entity),
            'stack' => $this->getRenderArrayForStackComponent($entity),
            default => [],
        };
    }

    protected function getRenderArrayForButtonComponent(UoComponent $entity): array
    {
        $build = [
            '#type' => 'component',
            '#component' => 'uo_components:button',
            '#props' => [],
            '#slots' => [],
        ];

        $this->buildPropsComponentClasses($build, $entity);
        $this->buildPropsData($build, $entity);
        $this->buildPropsWrapperClasses($build, $entity);

        $build['#props']['component_classes'][] = 'cta-button';
        if ($entity->hasField('button_type') && !$entity->get('button_type')->isEmpty()) {
            $build['#props']['component_classes'][] = match ($entity->get('button_type')->value) {
                'delete' => 'cta-button--delete',
                'submit' => 'cta-button--submit',
            };
        }

        $link = $entity->get('link')->getValue();
        if (!empty($link) && isset($link[0]) && !empty($link[0]['uri']) && !empty($link[0]['title'])) {
            $build['#props']['href'] = $link[0]['uri'];
            $build['#props']['target'] = $link[0]['options']['attributes']['target'] ?? '_self';
            $build['#props']['title'] = $link[0]['title'];
        }

        return $build;
    }

    protected function getRenderArrayForCaptionPhotoComponent(UoComponent $entity): array
    {
        $build = [
            '#type' => 'component',
            '#component' => 'uo_components:caption-photo',
            '#props' => [],
            '#slots' => [],
        ];

        $this->buildPropsComponentClasses($build, $entity);
        $this->buildPropsData($build, $entity);
        $this->buildPropsWrapperClasses($build, $entity);
        $this->buildSlotsPhoto($build, $entity);

        $build['#props']['component_classes'][] = 'caption-photo';

        if ($entity->hasField('header') && !$entity->get('header')->isEmpty()) {
            $build['#slots']['caption'] = [
                '#type' => 'markup',
                '#markup' => $entity->get('header')->value,
            ];
        }
        else {
            $build['#slots']['caption'] = ['#type' => 'markup', '#markup' => ''];
        }

        return $build;
    }

    protected function getRenderArrayForCardComponent(UoComponent $entity): array
    {
        $build = [
            '#type' => 'component',
            '#component' => 'uo_components:card',
            '#props' => [],
            '#slots' => [],
        ];

        $this->buildPropsComponentClasses($build, $entity);
        $this->buildPropsData($build, $entity);
        $this->buildPropsWrapperClasses($build, $entity);
        $this->buildSlotsBody($build, $entity);
        $this->buildSlotsHeader($build, $entity);
        $this->buildSlotsLink($build, $entity);
        $this->buildSlotsPhoto($build, $entity);

        $build['#props']['component_classes'][] = 'card-v2';

        return $build;
    }

    protected function getRenderArrayForEnvelopeComponent(UoComponent $entity): array
    {
        $build = [
            '#type' => 'component',
            '#component' => 'uo_components:envelope',
            '#props' => [],
            '#slots' => [],
        ];

        $this->buildPropsComponentClasses($build, $entity);
        $this->buildPropsData($build, $entity);
        $this->buildPropsWrapperClasses($build, $entity);
        $this->buildSlotsBody($build, $entity);
        $this->buildSlotsHeader($build, $entity);

        if ($entity->hasField('envelope_type') && !$entity->get('envelope_type')->isEmpty()) {
            $build['#props']['component_classes'][] = 'envelope';
            $build['#props']['component_classes'][] = match ($entity->get('envelope_type')->value) {
                'flush' => 'envelope--flush',
                'narrow' => 'envelope--narrow',
                default => 'envelope--standard',
            };
        }

        return $build;
    }

    protected function getRenderArrayForFeatureComponent(UoComponent $entity): array
    {
        $build = [
            '#type' => 'component',
            '#component' => 'uo_components:feature',
            '#props' => [],
            '#slots' => [],
        ];

        $this->buildPropsComponentClasses($build, $entity);
        $this->buildPropsData($build, $entity);
        $this->buildPropsWrapperClasses($build, $entity);
        $this->buildSlotsBody($build, $entity);
        $this->buildSlotsHeader($build, $entity);
        $this->buildSlotsLink($build, $entity);
        $this->buildSlotsPhoto($build, $entity);

        $build['#props']['component_classes'][] = 'envelope-feature';
        $build['#props']['component_classes'][] = match ($entity->get('feature_layout')->value) {
            'text_right' => 'envelope-feature--text-right',
            'text_top' => 'envelope-feature--text-top',
            'text_bottom' => 'envelope-feature--text-bottom',
            default => 'envelope-feature--text-left',
        };

        return $build;
    }

    protected function getRenderArrayForGridComponent(UoComponent $entity): array
    {
        $build = [
            '#type' => 'component',
            '#component' => 'uo_components:grid',
            '#props' => [],
            '#slots' => [],
        ];

        $this->buildPropsComponentClasses($build, $entity);
        $this->buildPropsData($build, $entity);
        $this->buildPropsWrapperClasses($build, $entity);

        $build['#props']['component_classes'][] = "grid-v2";

        $this->buildPropsComponentClassesAppendGridType($build, $entity);
        $this->buildPropsComponentClassesAppendGridSize($build, $entity);
        $this->buildPropsComponentClassesAppendGridGapSize($build, $entity);
        $this->buildPropsComponentClassesAppendGridColGapSize($build, $entity);
        $this->buildPropsComponentClassesAppendGridRowGapSize($build, $entity);

        $grid_items = $entity->get('grid_items');
        if ($grid_items instanceof EntityReferenceFieldItemListInterface) {
            foreach ($grid_items->referencedEntities() as $delta => $grid_item_entity) {
                $build['#slots']['grid_items'][] = [
                    '#type' => 'container',
                    '#attributes' => [
                        'class' => ['grid-v2__item', 'grid-v2__item__' . ($delta + 1)],
                    ],
                    'content' => $this->getRenderArrayForComponent($grid_item_entity),
                ];
            }
        }

        return $build;
    }

    protected function getRenderArrayForGalleryComponent(UoComponent $entity): array
    {
        $build = [
            '#type' => 'component',
            '#component' => 'uo_components:gallery',
            '#props' => [],
            '#slots' => [],
        ];

        $this->buildPropsComponentClasses($build, $entity);
        $this->buildPropsData($build, $entity);
        $this->buildPropsWrapperClasses($build, $entity);

        $build['#props']['component_classes'][] = "grid-v2";
        $build['#props']['component_classes'][] = "gallery-v2";

        $this->buildPropsComponentClassesAppendGridType($build, $entity);
        $this->buildPropsComponentClassesAppendGridSize($build, $entity);
        $this->buildPropsComponentClassesAppendGridGapSize($build, $entity);
        $this->buildPropsComponentClassesAppendGridColGapSize($build, $entity);
        $this->buildPropsComponentClassesAppendGridRowGapSize($build, $entity);

        $gallery_items = $entity->get('gallery_items');
        if ($gallery_items instanceof EntityReferenceFieldItemListInterface) {
            foreach ($gallery_items->referencedEntities() as $delta => $media_entity) {
                if ($media_entity instanceof Media) {
                    $file_entity = $media_entity->get('field_media_image')->entity;
                    if (!($file_entity instanceof File)) {
                        continue;
                    }
                    $file_url = Drupal::service('file_url_generator')->generateAbsoluteString($file_entity->getFileUri());

                    $build['#slots']['gallery_items'][] = [
                        '#type' => 'container',
                        '#attributes' => [
                            'class' => ['grid-v2__item', 'grid-v2__item__' . ($delta + 1)],
                        ],
                        'content' => [
                            '#type' => 'container',
                            '#attributes' => [
                                'class' => ['gallery-v2__item'],
                            ],
                            'thumbnail' => [
                                '#type' => 'container',
                                '#attributes' => [
                                    'class' => ['gallery-v2__item__thumbnail'],
                                ],
                                'image' => [
                                    '#type' => 'html_tag',
                                    '#tag' => 'img',
                                    '#attributes' => [
                                        'src' => $file_url,
                                        'alt' => $media_entity->get('field_media_image')->alt ?? $file_entity->getFilename(),
                                    ],
                                ],
                            ],
//                            'caption' => [
//                                '#type' => 'container',
//                                '#attributes' => [
//                                    'class' => ['gallery-v2__item__caption'],
//                                ],
//                                'text' => [
//                                    '#type' => 'markup',
//                                    '#markup' => $media_entity->getName()
//                                ],
//                            ],
//                            'group' => [
//                                '#type' => 'container',
//                                '#attributes' => [
//                                    'class' => ['gallery-v2__item__group'],
//                                ],
//                                'text' => [
//                                    '#type' => 'markup',
//                                    '#markup' => 'group1',
//                                ],
//                            ],
                        ],
                    ];
                }
            }
        }

        return $build;
    }

    protected function getRenderArrayForHeroComponent(UoComponent $entity): array
    {
        $build = [
            '#type' => 'component',
            '#component' => 'uo_components:hero',
            '#props' => [],
            '#slots' => [],
        ];

        $this->buildPropsComponentClasses($build, $entity);
        $this->buildPropsData($build, $entity);
        $this->buildPropsWrapperClasses($build, $entity);
        $this->buildSlotsBody($build, $entity);
        $this->buildSlotsHeader($build, $entity);
        $this->buildSlotsLink($build, $entity);
        $this->buildSlotsPhoto($build, $entity);

        $build['#props']['component_classes'][] = 'envelope-hero';
        $build['#props']['component_classes'][] = match ($entity->get('hero_layout')->value) {
            'text_left' => 'envelope-hero--text-left',
            'text_right' => 'envelope-hero--text-right',
            'text_top' => 'envelope-hero--text-top',
            'text_bottom' => 'envelope-hero--text-bottom',
            'text_hidden' => 'envelope-hero--no-text',
            default => 'envelope-hero--text-center',
        };

        if ($entity->hasField('hero_overlay') && !$entity->get('hero_overlay')->isEmpty()) {
            switch ($entity->get('hero_overlay')->value) {
                case 'box':
                    $build['#props']['component_classes'][] = 'envelope-hero--box';
                    break;
                case 'box_large':
                    $build['#props']['component_classes'][] = 'envelope-hero--box-large';
                    break;
                case 'full':
                    $build['#props']['component_classes'][] = 'envelope-hero--overlay-full';
                    break;
                case 'none':
                    $build['#props']['component_classes'][] = 'envelope-hero--no-shade';
                    break;
                case 'shade':
                    $build['#props']['component_classes'][] = 'envelope-hero--shade';
                    break;
            }
        }

        $image = $entity->get('photo');
        if ($image instanceof EntityReferenceFieldItemListInterface && !$image->isEmpty()) {
            $referenced_entities = $image->referencedEntities();
            $image_entity = reset($referenced_entities);
            if ($image_entity) {
                $file_url_generator = Drupal::service('file_url_generator');
                $build['#props']['background_url'] = $file_url_generator->generateAbsoluteString($image_entity->get('field_media_image')->entity->getFileUri());
            }
        }

        return $build;
    }

    protected function getRenderArrayForHtmlComponent(UoComponent $entity): array
    {
        $build = [
            '#type' => 'component',
            '#component' => 'uo_components:html',
            '#props' => [],
            '#slots' => [],
        ];

        $this->buildPropsData($build, $entity);

        if ($entity->hasField('html') && !$entity->get('html')->isEmpty()) {
            $build['#slots']['html'] = [
                '#type' => 'markup',
                '#markup' => $entity->get('html')->value,
            ];
        }

        return $build;
    }

    protected function getRenderArrayForPhotoButtonComponent(UoComponent $entity): array
    {
        $build = [
            '#type' => 'component',
            '#component' => 'uo_components:photo-button',
            '#props' => [],
            '#slots' => [],
        ];

        $this->buildPropsComponentClasses($build, $entity);
        $this->buildPropsData($build, $entity);
        $this->buildPropsWrapperClasses($build, $entity);
        $this->buildSlotsPhoto($build, $entity);

        $build['#props']['component_classes'][] = 'button-photo';

        $link = $entity->get('link')->getValue();
        if (!empty($link) && isset($link[0]) && !empty($link[0]['uri']) && !empty($link[0]['title'])) {
            $build['#props']['href'] = $link[0]['uri'];
            $build['#props']['target'] = $link[0]['options']['attributes']['target'] ?? '_self';
            $build['#slots']['caption'] = $link[0]['title'];
        }

        return $build;
    }

    protected function getRenderArrayForStackComponent(UoComponent $entity): array
    {
        $build = [
            '#type' => 'component',
            '#component' => 'uo_components:stack',
            '#props' => [],
            '#slots' => [],
        ];

        $this->buildPropsComponentClasses($build, $entity);
        $this->buildPropsData($build, $entity);
        $this->buildPropsWrapperClasses($build, $entity);

        $build['#props']['component_classes'][] = "stack";
        if ($entity->hasField('stack_layout') && !$entity->get('stack_layout')->isEmpty()) {
            switch ($entity->get('stack_layout')->value) {
                case 'horizontal':
                    $build['#props']['component_classes'][] = 'stack--horizontal';
                    break;
                case 'vertical':
                    $build['#props']['component_classes'][] = 'stack--vertical';
                    break;
            }
        }

        if ($entity->hasField('stack_gap_size') && !$entity->get('stack_gap_size')->isEmpty()) {
            switch ($entity->get('stack_gap_size')->value) {
                case 'none':
                    $build['#props']['component_classes'][] = 'stack--gap-none';
                    break;
                case 'sm':
                    $build['#props']['component_classes'][] = 'stack--gap-sm';
                    break;
                case 'md':
                    $build['#props']['component_classes'][] = 'stack--gap-md';
                    break;
                case 'lg':
                    $build['#props']['component_classes'][] = 'stack--gap-lg';
                    break;
            }
        }

        $stack_items = $entity->get('stack_items');
        if ($stack_items instanceof EntityReferenceFieldItemListInterface) {
            foreach ($stack_items->referencedEntities() as $delta => $stack_item_entity) {
                $build['#slots']['stack_items'][] = [
                    '#type' => 'container',
                    '#attributes' => [
                        'class' => ['stack__item', 'stack__item__' . ($delta + 1)],
                    ],
                    'content' => $this->getRenderArrayForComponent($stack_item_entity),
                ];
            }
        }

        return $build;
    }

    private function buildPropsComponentClasses(array &$build, UoComponent $entity): void
    {
        if ($entity->hasField('classes') && !$entity->get('classes')->isEmpty()) {
            foreach (explode(' ', $entity->get('classes')->value) as $class) {
                $build['#props']['component_classes'][] = $class;
            }
        }
        else {
            $build['#props']['component_classes'] = [];
        }
    }

    private function buildPropsData(array &$build, UoComponent $entity): void
    {
        $build['#props']['data'] = [
            'id' => $entity->uuid(),
            'type' => 'uo_component--' . $entity->bundle(),
        ];
    }

    private function buildPropsWrapperClasses(array &$build, UoComponent $entity): void
    {
        if ($entity->hasField('longform_layout') && !$entity->get('longform_layout')->isEmpty()) {
            $build['#props']['wrapper_classes'] = match ($entity->get('longform_layout')->value) {
                'content' => ['longform-layout', 'longform-layout--content'],
                'photos' => ['longform-layout', 'longform-layout--photos'],
                'wide' => ['longform-layout', 'longform-layout--wide'],
                'fullbleed' => ['longform-layout', 'longform-layout--fullbleed'],
                default => [],
            };
        }
        else {
            $build['#props']['wrapper_classes'] = [];
        }
    }

    private function buildPropsComponentClassesAppendGridType(array &$build, UoComponent $entity): void
    {
        if ($entity->hasField('grid_type') && !$entity->get('grid_type')->isEmpty()) {
            switch ($entity->get('grid_type')->value) {
                case '1col':
                    $build['#props']['component_classes'][] = 'grid-v2--1col';
                    break;
                case '2col':
                    $build['#props']['component_classes'][] = 'grid-v2--2col';
                    break;
                case '3col':
                    $build['#props']['component_classes'][] = 'grid-v2--3col';
                    break;
                case '4col':
                    $build['#props']['component_classes'][] = 'grid-v2--4col';
                    break;
                case '5col':
                    $build['#props']['component_classes'][] = 'grid-v2--5col';
                    break;
                case '2col-10-90':
                    $build['#props']['component_classes'][] = 'grid-v2--2col-10-90';
                    break;
                case '2col-20-80':
                    $build['#props']['component_classes'][] = 'grid-v2--2col-20-80';
                    break;
                case '2col-30-70':
                    $build['#props']['component_classes'][] = 'grid-v2--2col-30-70';
                    break;
                case '2col-40-60':
                    $build['#props']['component_classes'][] = 'grid-v2--2col-40-60';
                    break;
                case '2col-50-50':
                    $build['#props']['component_classes'][] = 'grid-v2--2col-50-50';
                    break;
                case '2col-60-40':
                    $build['#props']['component_classes'][] = 'grid-v2--2col-60-40';
                    break;
                case '2col-70-30':
                    $build['#props']['component_classes'][] = 'grid-v2--2col-70-30';
                    break;
                case '2col-80-20':
                    $build['#props']['component_classes'][] = 'grid-v2--2col-80-20';
                    break;
                case '2col-90-10':
                    $build['#props']['component_classes'][] = 'grid-v2--2col-90-10';
                    break;
            }
        }
    }

    private function buildPropsComponentClassesAppendGridSize(array &$build, UoComponent $entity): void
    {
        if ($entity->hasField('grid_size') && !$entity->get('grid_size')->isEmpty()) {
            switch ($entity->get('grid_size')->value) {
                case 'xs':
                    $build['#props']['component_classes'][] = 'grid-v2--xs';
                    break;
                case 'sm':
                    $build['#props']['component_classes'][] = 'grid-v2--sm';
                    break;
                case 'md':
                    $build['#props']['component_classes'][] = 'grid-v2--md';
                    break;
                case 'lg':
                    $build['#props']['component_classes'][] = 'grid-v2--lg';
                    break;
                case 'xl':
                    $build['#props']['component_classes'][] = 'grid-v2--xl';
                    break;
                case '2xl':
                    $build['#props']['component_classes'][] = 'grid-v2--2xl';
                    break;
                case '3xl':
                    $build['#props']['component_classes'][] = 'grid-v2--3xl';
                    break;
                case '4xl':
                    $build['#props']['component_classes'][] = 'grid-v2--4xl';
                    break;
            }
        }
    }

    private function buildPropsComponentClassesAppendGridGapSize(array &$build, UoComponent $entity): void
    {
        if ($entity->hasField('grid_gap_size') && !$entity->get('grid_gap_size')->isEmpty()) {
            switch ($entity->get('grid_gap_size')->value) {
                case 'none':
                    $build['#props']['component_classes'][] = 'grid-v2--gap-none';
                    break;
                case 'sm':
                    $build['#props']['component_classes'][] = 'grid-v2--gap-sm';
                    break;
                case 'md':
                    $build['#props']['component_classes'][] = 'grid-v2--gap-md';
                    break;
                case 'lg':
                    $build['#props']['component_classes'][] = 'grid-v2--gap-lg';
                    break;
            }
        }
    }

    private function buildPropsComponentClassesAppendGridColGapSize(array &$build, UoComponent $entity): void
    {
        if ($entity->hasField('grid_col_gap_size') && !$entity->get('grid_col_gap_size')->isEmpty()) {
            switch ($entity->get('grid_col_gap_size')->value) {
                case 'none':
                    $build['#props']['component_classes'][] = 'grid-v2--col-gap-none';
                    break;
                case 'sm':
                    $build['#props']['component_classes'][] = 'grid-v2--col-gap-sm';
                    break;
                case 'md':
                    $build['#props']['component_classes'][] = 'grid-v2--col-gap-md';
                    break;
                case 'lg':
                    $build['#props']['component_classes'][] = 'grid-v2--col-gap-lg';
                    break;
            }
        }
    }

    private function buildPropsComponentClassesAppendGridRowGapSize(array &$build, UoComponent $entity): void
    {
        if ($entity->hasField('grid_row_gap_size') && !$entity->get('grid_row_gap_size')->isEmpty()) {
            switch ($entity->get('grid_row_gap_size')->value) {
                case 'none':
                    $build['#props']['component_classes'][] = 'grid-v2--row-gap-none';
                    break;
                case 'sm':
                    $build['#props']['component_classes'][] = 'grid-v2--row-gap-sm';
                    break;
                case 'md':
                    $build['#props']['component_classes'][] = 'grid-v2--row-gap-md';
                    break;
                case 'lg':
                    $build['#props']['component_classes'][] = 'grid-v2--row-gap-lg';
                    break;
            }
        }
    }

    private function buildSlotsBody(array &$build, UoComponent $entity): void
    {
        if ($entity->hasField('body') && !$entity->get('body')->isEmpty()) {
            $build['#slots']['body'] = [
                '#type' => 'markup',
                '#markup' => $entity->get('body')->value,
            ];
        }
        else {
            $build['#slots']['body'] = ['#type' => 'markup', '#markup' => ''];
        }
    }

    private function buildSlotsHeader(array &$build, UoComponent $entity): void
    {
        if ($entity->hasField('header') && !$entity->get('header')->isEmpty()) {
            $build['#slots']['header'] = [
                '#type' => 'markup',
                '#markup' => $entity->get('header')->value,
            ];
        }
        else {
            $build['#slots']['header'] = ['#type' => 'markup', '#markup' => ''];
        }
    }

    private function buildSlotsLink(array &$build, UoComponent $entity): void
    {
        $link = $entity->get('link')->getValue();
        if (!empty($link) && isset($link[0]) && !empty($link[0]['uri']) && !empty($link[0]['title'])) {
            $build['#slots']['link'] = [
                '#type' => 'link',
                '#title' => $link[0]['title'],
                '#url' => Url::fromUri($link[0]['uri']),
                '#attributes' => [
                    'class' => ['cta-button'],
                    'target' => $link[0]['options']['attributes']['target'] ?? '_self',
                ],
            ];
        }
        else {
            $build['#slots']['link'] = ['#type' => 'markup', '#markup' => ''];
        }
    }

    private function buildSlotsPhoto(array &$build, UoComponent $entity): void
    {
        $image = $entity->get('photo');
        if ($image instanceof EntityReferenceFieldItemListInterface && !$image->isEmpty()) {
            $referenced_entities = $image->referencedEntities();
            $media_entity = reset($referenced_entities);
            $file_entity = $media_entity->get('field_media_image')->entity;
            if ($file_entity instanceof File) {
                $file_url = Drupal::service('file_url_generator')->generateAbsoluteString($file_entity->getFileUri());
                $build['#slots']['photo'] = [
                    '#type' => 'html_tag',
                    '#tag' => 'img',
                    '#attributes' => [
                        'src' => $file_url,
                        'alt' => $media_entity->get('field_media_image')->alt ?? $file_entity->getFilename(),
                    ],
                ];
            }
        }
        else {
            $build['#slots']['photo'] = ['#type' => 'markup', '#markup' => ''];
        }
    }
}
