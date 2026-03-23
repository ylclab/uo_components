import { FieldUoComponentsFull, type OperationAdd, type OperationCopy, type OperationDelete, type OperationEdit, type UoComponentCompoundTypes } from '@ylclab/uo.components'

import { useErrorMonitor } from '@/hooks/error-monitor'
import { useSessionMonitor } from '@/hooks/session-monitor'
import { useAppStore } from '@/store'

export interface AppProps {
  name: string
}

export function Field() {
  const baseUrl = useAppStore(state => state.config.baseUrl)
  const csrfToken = useAppStore(state => state.config.csrfToken)
  const id = useAppStore(state => state.config.id)
  const type = useAppStore(state => state.config.type)
  const fieldName = useAppStore(state => state.config.fieldName)
  const operation = useAppStore(state => state.operation)
  const uoComponents = useAppStore(state => state.uoComponents)
  const set = useAppStore(state => state.set)
  const setError = useAppStore(state => state.setError)

  useErrorMonitor()
  useSessionMonitor()

  const onChange = (uoComponents: UoComponentCompoundTypes[]) => {
    set({ uoComponents })
  }

  const onOperationChange = (operation: OperationAdd | OperationCopy | OperationDelete | OperationEdit | null) => {
    set({ operation })
  }

  return (
    <FieldUoComponentsFull
      baseUrl={baseUrl}
      editable
      fieldName={fieldName}
      id={id}
      operation={operation}
      options={{ headers: { 'X-CSRF-Token': csrfToken }, credentials: 'include' }}
      type={type}
      uoComponents={uoComponents}
      onChange={onChange}
      onError={setError}
      onOperationChange={onOperationChange}
    />
  )
}
