<?php

namespace Drupal\uo_components\Provision;

/**
 * Canonical schema for required UO component bundles and fields.
 */
class UoComponentManifest
{
    /**
     * Returns required field storage and bundle field definitions.
     */
    public function definition(): array
    {
        return [
            'field_storage' => [
                'body' => [
                    'type' => 'text_long',
                    'module' => 'text',
                    'settings' => [],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'button_type' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            'delete' => 'Delete Button',
                            'submit' => 'Submit Button',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'classes' => [
                    'type' => 'string',
                    'module' => 'core',
                    'settings' => [
                        'max_length' => 255,
                        'case_sensitive' => false,
                        'is_ascii' => false,
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'envelope_type' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            'flush' => 'Flush',
                            'narrow' => 'Narrow',
                            'standard' => 'Standard',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'feature_layout' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            'text_left' => 'Text Left',
                            'text_right' => 'Text Right',
                            'text_top' => 'Text Top',
                            'text_bottom' => 'Text Bottom',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'gallery_items' => [
                    'type' => 'entity_reference',
                    'module' => 'core',
                    'settings' => [
                        'target_type' => 'media',
                    ],
                    'cardinality' => -1,
                    'translatable' => true,
                ],
                'grid_col_gap_size' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            'none' => 'None',
                            'sm' => 'Small',
                            'md' => 'Medium',
                            'lg' => 'Large',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'grid_gap_size' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            'none' => 'None',
                            'sm' => 'Small',
                            'md' => 'Medium',
                            'lg' => 'Large',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'grid_items' => [
                    'type' => 'entity_reference_revisions',
                    'module' => 'entity_reference_revisions',
                    'settings' => [
                        'target_type' => 'uo_component',
                    ],
                    'cardinality' => -1,
                    'translatable' => true,
                ],
                'grid_remainder' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            'default' => 'Default',
                            'expand' => 'Expand',
                            'center' => 'Center',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'grid_row_gap_size' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            'none' => 'None',
                            'sm' => 'Small',
                            'md' => 'Medium',
                            'lg' => 'Large',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'grid_size' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            'xs' => 'Extra Small',
                            'sm' => 'Small',
                            'md' => 'Medium',
                            'lg' => 'Large',
                            'xl' => 'Extra Large',
                            '2xl' => '2X Large',
                            '3xl' => '3X Large',
                            '4xl' => '4X Large',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'grid_type' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            '1col' => 'One Column',
                            '2col' => 'Two Columns',
                            '3col' => 'Three Columns',
                            '4col' => 'Four Columns',
                            '5col' => 'Five Columns',
                            '2col-10-90' => 'Two Columns (10-90)',
                            '2col-20-80' => 'Two Columns (20-80)',
                            '2col-30-70' => 'Two Columns (30-70)',
                            '2col-40-60' => 'Two Columns (40-60)',
                            '2col-50-50' => 'Two Columns (50-50)',
                            '2col-60-40' => 'Two Columns (60-40)',
                            '2col-70-30' => 'Two Columns (70-30)',
                            '2col-80-20' => 'Two Columns (80-20)',
                            '2col-90-10' => 'Two Columns (90-10)',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'header' => [
                    'type' => 'string',
                    'module' => 'core',
                    'settings' => [
                        'max_length' => 255,
                        'case_sensitive' => false,
                        'is_ascii' => false,
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'hero_layout' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            'text_center' => 'Text Center',
                            'text_left' => 'Text Left',
                            'text_right' => 'Text Right',
                            'text_top' => 'Text Top',
                            'text_bottom' => 'Text Bottom',
                            'text_hidden' => 'Text Hidden',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'hero_overlay' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            'box' => 'Boxed Text',
                            'box_large' => 'Boxed Text Padded',
                            'full' => 'Full Overlay',
                            'gradient' => 'Gradient Overlay',
                            'none' => 'No Overlay',
                            'shade' => 'Solid Overlay',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'html' => [
                    'type' => 'string_long',
                    'module' => 'core',
                    'settings' => [
                        'case_sensitive' => false,
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'link' => [
                    'type' => 'link',
                    'module' => 'link',
                    'settings' => [],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'longform_layout' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            'none' => 'None',
                            'content' => 'Content',
                            'photos' => 'Photos',
                            'wide' => 'Wide',
                            'fullbleed' => 'Full Bleed',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'photo' => [
                    'type' => 'entity_reference',
                    'module' => 'core',
                    'settings' => [
                        'target_type' => 'media',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'stack_gap_size' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            'none' => 'None',
                            'sm' => 'Small',
                            'md' => 'Medium',
                            'lg' => 'Large',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
                'stack_items' => [
                    'type' => 'entity_reference_revisions',
                    'module' => 'entity_reference_revisions',
                    'settings' => [
                        'target_type' => 'uo_component',
                    ],
                    'cardinality' => -1,
                    'translatable' => true,
                ],
                'stack_layout' => [
                    'type' => 'list_string',
                    'module' => 'options',
                    'settings' => [
                        'allowed_values' => [
                            'horizontal' => 'Horizontal',
                            'vertical' => 'Vertical',
                        ],
                        'allowed_values_function' => '',
                    ],
                    'cardinality' => 1,
                    'translatable' => true,
                ],
            ],
            'bundles' => [
                'button' => [
                    'label' => 'Button',
                    'fields' => [
                        'button_type' => [
                            'label' => 'Button Type',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'classes' => [
                            'label' => 'Classes',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'link' => [
                            'label' => 'Link',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [
                                'title' => 1,
                                'link_type' => 17,
                            ],
                        ],
                        'longform_layout' => [
                            'label' => 'Longform Layout',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                    ],
                ],
                'caption_photo' => [
                    'label' => 'Captioned Photo',
                    'fields' => [
                        'classes' => [
                            'label' => 'Classes',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'header' => [
                            'label' => 'Caption',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'longform_layout' => [
                            'label' => 'Longform Layout',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'photo' => [
                            'label' => 'Photo',
                            'required' => false,
                            'translatable' => false,
                            'settings' => [
                                'handler' => 'default:media',
                                'handler_settings' => [
                                    'sort' => [
                                        'field' => '_none',
                                        'direction' => 'ASC',
                                    ],
                                    'auto_create' => false,
                                    'auto_create_bundle' => '',
                                ],
                            ],
                        ],
                    ],
                ],
                'card' => [
                    'label' => 'Card',
                    'fields' => [
                        'body' => [
                            'label' => 'Body',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'classes' => [
                            'label' => 'Classes',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'header' => [
                            'label' => 'Header',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'link' => [
                            'label' => 'Link',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [
                                'title' => 1,
                                'link_type' => 17,
                            ],
                        ],
                        'longform_layout' => [
                            'label' => 'Longform Layout',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'photo' => [
                            'label' => 'Photo',
                            'required' => false,
                            'translatable' => false,
                            'settings' => [
                                'handler' => 'default:media',
                                'handler_settings' => [
                                    'sort' => [
                                        'field' => '_none',
                                        'direction' => 'ASC',
                                    ],
                                    'auto_create' => false,
                                    'auto_create_bundle' => '',
                                ],
                            ],
                        ],
                    ],
                ],
                'envelope' => [
                    'label' => 'Envelope',
                    'fields' => [
                        'body' => [
                            'label' => 'Body',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'classes' => [
                            'label' => 'Classes',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'envelope_type' => [
                            'label' => 'Envelope Type',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'header' => [
                            'label' => 'Header',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'longform_layout' => [
                            'label' => 'Longform Layout',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                    ],
                ],
                'feature' => [
                    'label' => 'Feature',
                    'fields' => [
                        'body' => [
                            'label' => 'Body',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'classes' => [
                            'label' => 'Classes',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'feature_layout' => [
                            'label' => 'Feature Layout',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'header' => [
                            'label' => 'Header',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'link' => [
                            'label' => 'Link',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [
                                'title' => 1,
                                'link_type' => 17,
                            ],
                        ],
                        'longform_layout' => [
                            'label' => 'Longform Layout',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'photo' => [
                            'label' => 'Photo',
                            'required' => false,
                            'translatable' => false,
                            'settings' => [
                                'handler' => 'default:media',
                                'handler_settings' => [
                                    'sort' => [
                                        'field' => '_none',
                                        'direction' => 'ASC',
                                    ],
                                    'auto_create' => false,
                                    'auto_create_bundle' => '',
                                ],
                            ],
                        ],
                    ],
                ],
                'gallery' => [
                    'label' => 'Gallery',
                    'fields' => [
                        'classes' => [
                            'label' => 'Classes',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'gallery_items' => [
                            'label' => 'Gallery Items',
                            'required' => false,
                            'translatable' => false,
                            'settings' => [
                                'handler' => 'default:media',
                                'handler_settings' => [
                                    'sort' => [
                                        'field' => '_none',
                                        'direction' => 'ASC',
                                    ],
                                    'auto_create' => false,
                                    'auto_create_bundle' => '',
                                ],
                            ],
                        ],
                        'grid_col_gap_size' => [
                            'label' => 'Grid Column Gap Size',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'grid_gap_size' => [
                            'label' => 'Grid Gap Size',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'grid_remainder' => [
                            'label' => 'Grid Remainder',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'grid_row_gap_size' => [
                            'label' => 'Grid Row Gap Size',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'grid_size' => [
                            'label' => 'Grid Size',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'grid_type' => [
                            'label' => 'Grid Type',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'longform_layout' => [
                            'label' => 'Longform Layout',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                    ],
                ],
                'grid' => [
                    'label' => 'Grid',
                    'fields' => [
                        'classes' => [
                            'label' => 'Classes',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'grid_col_gap_size' => [
                            'label' => 'Grid Column Gap Size',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'grid_gap_size' => [
                            'label' => 'Grid Gap Size',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'grid_items' => [
                            'label' => 'Grid Items',
                            'required' => false,
                            'translatable' => false,
                            'settings' => [
                                'handler' => 'default:uo_component',
                                'handler_settings' => [
                                    'target_bundles' => [
                                        'button' => 'button',
                                        'caption_photo' => 'caption_photo',
                                        'card' => 'card',
                                        'envelope' => 'envelope',
                                        'feature' => 'feature',
                                        'grid' => 'grid',
                                        'hero' => 'hero',
                                        'html' => 'html',
                                        'photo_button' => 'photo_button',
                                        'stack' => 'stack',
                                    ],
                                    'negate' => 0,
                                ],
                            ],
                        ],
                        'grid_remainder' => [
                            'label' => 'Grid Remainder',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'grid_row_gap_size' => [
                            'label' => 'Grid Row Gap Size',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'grid_size' => [
                            'label' => 'Grid Size',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'grid_type' => [
                            'label' => 'Grid Type',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'longform_layout' => [
                            'label' => 'Longform Layout',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                    ],
                ],
                'hero' => [
                    'label' => 'Hero',
                    'fields' => [
                        'body' => [
                            'label' => 'Body',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'classes' => [
                            'label' => 'Classes',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'header' => [
                            'label' => 'Header',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'hero_layout' => [
                            'label' => 'Hero Layout',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'hero_overlay' => [
                            'label' => 'Hero Overlay',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'link' => [
                            'label' => 'Link',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [
                                'title' => 1,
                                'link_type' => 17,
                            ],
                        ],
                        'longform_layout' => [
                            'label' => 'Longform Layout',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'photo' => [
                            'label' => 'Photo',
                            'required' => false,
                            'translatable' => false,
                            'settings' => [
                                'handler' => 'default:media',
                                'handler_settings' => [
                                    'sort' => [
                                        'field' => '_none',
                                        'direction' => 'ASC',
                                    ],
                                    'auto_create' => false,
                                    'auto_create_bundle' => '',
                                ],
                            ],
                        ],
                    ],
                ],
                'html' => [
                    'label' => 'HTML',
                    'fields' => [
                        'html' => [
                            'label' => 'HTML',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                    ],
                ],
                'photo_button' => [
                    'label' => 'Photo Button',
                    'fields' => [
                        'classes' => [
                            'label' => 'Classes',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'link' => [
                            'label' => 'Link',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [
                                'title' => 1,
                                'link_type' => 17,
                            ],
                        ],
                        'longform_layout' => [
                            'label' => 'Longform Layout',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'photo' => [
                            'label' => 'Photo',
                            'required' => false,
                            'translatable' => false,
                            'settings' => [
                                'handler' => 'default:media',
                                'handler_settings' => [
                                    'sort' => [
                                        'field' => '_none',
                                        'direction' => 'ASC',
                                    ],
                                    'auto_create' => false,
                                    'auto_create_bundle' => '',
                                ],
                            ],
                        ],
                    ],
                ],
                'stack' => [
                    'label' => 'Stack',
                    'fields' => [
                        'classes' => [
                            'label' => 'Classes',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'longform_layout' => [
                            'label' => 'Longform Layout',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'stack_gap_size' => [
                            'label' => 'Stack Gap Size',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                        'stack_items' => [
                            'label' => 'Stack Items',
                            'required' => false,
                            'translatable' => false,
                            'settings' => [
                                'handler' => 'default:uo_component',
                                'handler_settings' => [
                                    'target_bundles' => [
                                        'button' => 'button',
                                        'caption_photo' => 'caption_photo',
                                        'card' => 'card',
                                        'envelope' => 'envelope',
                                        'feature' => 'feature',
                                        'grid' => 'grid',
                                        'hero' => 'hero',
                                        'html' => 'html',
                                        'photo_button' => 'photo_button',
                                        'stack' => 'stack',
                                    ],
                                    'negate' => 0,
                                ],
                            ],
                        ],
                        'stack_layout' => [
                            'label' => 'Stack Layout',
                            'required' => false,
                            'translatable' => true,
                            'settings' => [],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Field names that should target only the image media bundle when available.
     */
    public function imageTargetBundleFields(): array
    {
        return [
            'gallery_items' => ['gallery'],
            'photo' => ['caption_photo', 'card', 'feature', 'hero', 'photo_button'],
        ];
    }
}
