import { assertIsEntityBase, type EntityBase, type JsonApiOptions, useEntityLoad, useEntityUpdate } from '@ylclab/drupal.core'
import {
  appendMetadataToFieldUoComponentItems,
  assertIsUoComponentCompoundTypes,
  type FieldUoComponent,
  FieldUoComponentsFull,
  type UoComponentCompoundTypes,
  type UoComponentFullSlotProps,
} from '@ylclab/uo.components'
import { DrupalJsonApiParams } from 'drupal-jsonapi-params'

import { useSessionMonitor } from '@/hooks/session-monitor'
import { useAppStore } from '@/store'

export interface AppProps {
  name: string
}

function guard(value: unknown): asserts value is EntityBase & Record<string, unknown> {
  assertIsEntityBase(value)
}

export function Field() {
  const baseUrl = useAppStore((state) => state.config.baseUrl)
  const csrfToken = useAppStore((state) => state.config.csrfToken)
  const fieldName = useAppStore((state) => state.config.fieldName)
  const id = useAppStore((state) => state.config.id)
  const resourceType = useAppStore((state) => state.config.type)
  const setMessage = useAppStore((state) => state.setMessage)

  useSessionMonitor()

  const options: JsonApiOptions['options'] = csrfToken ? { headers: { 'X-CSRF-Token': csrfToken }, credentials: 'include' } : undefined
  const params = new DrupalJsonApiParams().addInclude([fieldName])

  const { entity, entitySet, load } = useEntityLoad({
    baseUrl,
    guard,
    id,
    loadAutomatically: true,
    options,
    params,
    resourceType,
    onError: (error) => setMessage({ severity: 'error', text: error instanceof Error ? error.message : 'Unknown error' }),
  })

  const { updateEntity } = useEntityUpdate({
    baseUrl,
    guard,
    id,
    options,
    params,
    resourceType,
  })

  const slotProps: UoComponentFullSlotProps = {
    baseUrl,
    options,
  }

  const value = entity ? (Array.isArray(entity[fieldName]) ? entity[fieldName] : [entity[fieldName]]) : null

  const onChange = async (value: FieldUoComponent | null) => {
    try {
      if (!entity) throw new Error('Entity not loaded')

      const uoComponents: UoComponentCompoundTypes[] = []
      for (const component of Array.isArray(value) ? value : [value]) {
        try {
          assertIsUoComponentCompoundTypes(component)
          uoComponents.push(component)
        } catch (error) {
          setMessage({ severity: 'error', text: error instanceof Error ? error.message : 'Unknown error', toast: true })
        }
      }

      entitySet({
        ...entity,
        [fieldName]: uoComponents,
      })

      const updated = await updateEntity({
        stuff: {
          id,
          type: resourceType,
          [fieldName]: appendMetadataToFieldUoComponentItems(value),
          relationshipNames: [fieldName],
        },
      })

      entitySet(updated)
      setMessage({ severity: 'success', text: 'Field updated successfully', toast: true })

      await load()
    } catch (error) {
      setMessage({ severity: 'error', text: error instanceof Error ? error.message : 'Unknown error', toast: true })
    }
  }

  return (
    <FieldUoComponentsFull
      baseUrl={baseUrl}
      editable
      options={options}
      slotProps={slotProps}
      value={value}
      AddProps={{ addLabel: 'Add a Component' }}
      onChange={onChange}
      onError={(error) => setMessage({ severity: 'error', text: error instanceof Error ? error.message : 'Unknown error', toast: true })}
    />
  )
}
