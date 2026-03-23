<?php

namespace Drupal\uo_components\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Access\AccessResultReasonInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Session\AccountInterface;

class UoComponentAccessControlHandler extends EntityAccessControlHandler
{
    protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
    {
        if ($operation === 'view') {
            $published = $entity->isPublished();
            if (!$published) {
                return AccessResult::allowedIfHasPermission($account, 'view unpublished UO components')
                    ->addCacheableDependency($entity);
            }

            return AccessResult::allowedIfHasPermission($account, 'view UO components')
                ->addCacheableDependency($entity);
        }

        if ($operation === 'preview') {
            return AccessResult::allowedIfHasPermission($account, 'preview UO components')
                ->orIf(AccessResult::allowedIfHasPermission($account, 'edit any UO components'))
                ->orIf(
                    AccessResult::allowedIfHasPermission($account, 'edit own UO components')
                        ->andIf(AccessResult::allowedIf($entity->getOwnerId() == $account->id()))
                )
                ->addCacheableDependency($entity);
        }

        switch ($operation) {
            case 'update':
                $result = AccessResult::allowedIfHasPermission($account, 'edit any UO components');
                if ($entity->getOwnerId() == $account->id()) {
                    $result = $result->orIf(
                        AccessResult::allowedIfHasPermission($account, 'edit own UO components')
                    );
                }

                return $result->addCacheableDependency($entity);

            case 'delete':
                $result = AccessResult::allowedIfHasPermission($account, 'delete any UO components');
                if ($entity->getOwnerId() == $account->id()) {
                    $result = $result->orIf(
                        AccessResult::allowedIfHasPermission($account, 'delete own UO components')
                    );
                }

                return $result->addCacheableDependency($entity);

            case 'view_revision':
                return AccessResult::allowedIfHasPermission($account, 'view UO component revisions')
                    ->addCacheableDependency($entity);

            case 'revert':
                return AccessResult::allowedIfHasPermission($account, 'revert UO component revisions')
                    ->addCacheableDependency($entity);

            case 'delete_revision':
                return AccessResult::allowedIfHasPermission($account, 'delete UO component revisions')
                    ->addCacheableDependency($entity);

            default:
                return AccessResult::neutral();
        }
    }

    protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = null): AccessResultReasonInterface|AccessResult|AccessResultInterface
    {
        return AccessResult::allowedIfHasPermission($account, 'create UO components');
    }

    protected function checkFieldAccess($operation, FieldDefinitionInterface $field_definition, AccountInterface $account, ?FieldItemListInterface $items = null): AccessResultReasonInterface|AccessResult|AccessResultInterface
    {
        if ($field_definition->getName() === 'revision_log_message') {
            if ($operation === 'view') {
                return AccessResult::allowedIfHasPermission($account, 'view UO component revisions');
            }
        }

        return parent::checkFieldAccess($operation, $field_definition, $account, $items);
    }

}
