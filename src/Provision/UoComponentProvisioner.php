<?php

namespace Drupal\uo_components\Provision;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Psr\Log\LoggerInterface;

/**
 * Provisions required UO component bundles and fields from the manifest.
 */
class UoComponentProvisioner
{
    public function __construct(
        private readonly EntityTypeManagerInterface $entityTypeManager,
        private readonly UoComponentManifest $manifest,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Ensures all module-owned bundles and fields exist with expected settings.
     */
    public function provision(): void
    {
        $definition = $this->manifest->definition();

        $this->provisionBundles($definition['bundles'] ?? []);
        $this->provisionFieldStorage($definition['field_storage'] ?? []);
        $this->provisionBundleFields($definition['bundles'] ?? [], $definition['field_storage'] ?? []);
        $this->applyImageMediaTargetBundleAdjustments();
    }

    private function provisionBundles(array $bundles): void
    {
        $bundleStorage = $this->entityTypeManager->getStorage('uo_component_type');

        foreach ($bundles as $bundleId => $bundleDefinition) {
            $bundle = $bundleStorage->load($bundleId);
            if (!$bundle) {
                $bundleStorage->create([
                    'id' => $bundleId,
                    'label' => $bundleDefinition['label'],
                ])->save();
                continue;
            }

            if ($bundle->label() !== $bundleDefinition['label']) {
                $bundle->set('label', $bundleDefinition['label']);
                $bundle->save();
            }
        }
    }

    private function provisionFieldStorage(array $fieldStorageDefinitions): void
    {
        foreach ($fieldStorageDefinitions as $fieldName => $fieldStorageDefinition) {
            $storage = FieldStorageConfig::loadByName('uo_component', $fieldName);
            if (!$storage) {
                $storage = FieldStorageConfig::create([
                    'id' => "uo_component.$fieldName",
                    'field_name' => $fieldName,
                    'entity_type' => 'uo_component',
                    'type' => $fieldStorageDefinition['type'],
                    'module' => $fieldStorageDefinition['module'],
                    'settings' => $fieldStorageDefinition['settings'] ?? [],
                    'cardinality' => $fieldStorageDefinition['cardinality'] ?? 1,
                    'translatable' => (bool)($fieldStorageDefinition['translatable'] ?? false),
                ]);
                $storage->save();
                continue;
            }

            $storage->set('settings', $fieldStorageDefinition['settings'] ?? []);
            $storage->setCardinality($fieldStorageDefinition['cardinality'] ?? 1);
            $storage->setTranslatable((bool)($fieldStorageDefinition['translatable'] ?? false));
            $storage->save();
        }
    }

    private function provisionBundleFields(array $bundleDefinitions, array $fieldStorageDefinitions): void
    {
        foreach ($bundleDefinitions as $bundleId => $bundleDefinition) {
            foreach ($bundleDefinition['fields'] ?? [] as $fieldName => $fieldDefinition) {
                if (!isset($fieldStorageDefinitions[$fieldName])) {
                    $this->logger->warning('Skipped field {field_name} for bundle {bundle}: no field storage definition found in manifest.', [
                        'field_name' => $fieldName,
                        'bundle' => $bundleId,
                    ]);
                    continue;
                }

                $field = FieldConfig::loadByName('uo_component', $bundleId, $fieldName);
                if (!$field) {
                    $field = FieldConfig::create([
                        'id' => "uo_component.$bundleId.$fieldName",
                        'field_name' => $fieldName,
                        'entity_type' => 'uo_component',
                        'bundle' => $bundleId,
                        'label' => $fieldDefinition['label'],
                        'required' => (bool)($fieldDefinition['required'] ?? false),
                        'translatable' => (bool)($fieldDefinition['translatable'] ?? false),
                        'settings' => $fieldDefinition['settings'] ?? [],
                        'field_type' => $fieldStorageDefinitions[$fieldName]['type'],
                    ]);
                    $field->save();
                    continue;
                }

                $field->setLabel($fieldDefinition['label']);
                $field->setRequired((bool)($fieldDefinition['required'] ?? false));
                $field->setTranslatable((bool)($fieldDefinition['translatable'] ?? false));
                $field->set('settings', $fieldDefinition['settings'] ?? []);
                $field->save();
            }
        }
    }

    private function applyImageMediaTargetBundleAdjustments(): void
    {
        $mediaTypeStorage = $this->entityTypeManager->getStorage('media_type');
        if (!$mediaTypeStorage->load('image')) {
            return;
        }

        foreach ($this->manifest->imageTargetBundleFields() as $fieldName => $bundles) {
            foreach ($bundles as $bundle) {
                $config = FieldConfig::loadByName('uo_component', $bundle, $fieldName);
                if (!$config) {
                    continue;
                }

                $handlerSettings = $config->getSetting('handler_settings') ?: [];
                $handlerSettings['target_bundles'] = ['image' => 'image'];
                $config->setSetting('handler_settings', $handlerSettings);
                $config->save();
            }
        }
    }
}

