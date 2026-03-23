import { type EntityBase } from '@ylclab/drupal.core'
import { assertIsHTMLElement, assertIsString, assertIsStringRecord, assertWithMessage } from '@ylclab/typescript-utils'
import { createRoot } from 'react-dom/client'

import { App } from '@/components/app'

declare global {
  interface Window {
    Drupal: unknown
    drupalSettings: unknown
  }
}

try {
  assertWithMessage(window.drupalSettings, 'Drupal settings not found', assertIsStringRecord)
  assertWithMessage(window.drupalSettings.uo_components, 'Uo Components settings not found', assertIsStringRecord)

  const baseUrl = window.drupalSettings.uo_components.baseUrl
  assertIsString(baseUrl)

  const instances = window.drupalSettings.uo_components.instances
  assertWithMessage(instances, 'Instances not found', assertIsStringRecord)

  for (const [id, settings] of Object.entries(instances)) {
    try {
      assertWithMessage(settings, `Settings for instance ${id} not found`, assertIsStringRecord)

      const element = document.getElementById(id)
      assertWithMessage(element, `Element with ID ${id} not found`, assertIsHTMLElement)

      assertWithMessage(settings.entity_type, `Entity type for instance ${id} not found`, assertIsString)
      assertWithMessage(settings.entity_uuid, `Entity UUID for instance ${id} not found`, assertIsString)
      assertWithMessage(settings.field_name, `Field name for instance ${id} not found`, assertIsString)
      assertWithMessage(settings.view_mode, `View mode for instance ${id} not found`, assertIsString)

      createRoot(element).render((
        <App
          name={id}
          state={{
            config: {
              baseUrl,
              csrfToken: '',
              id: settings.entity_uuid,
              type: settings.entity_type as EntityBase['type'],
              fieldName: settings.field_name,
              viewMode: settings.view_mode,
            },
            error: null,
            operation: null,
            uoComponents: [],
          }}
        />
      ))
    }
    catch (error) {
      // eslint-disable-next-line no-console
      console.error(error)
    }
  }
}
catch (error) {
  // eslint-disable-next-line no-console
  console.error(error)
}
