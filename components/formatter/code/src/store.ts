import { type EntityBase } from '@ylclab/drupal.core'
import type { OperationAdd, OperationCopy, OperationDelete, OperationEdit, UoComponentCompoundTypes } from '@ylclab/uo.components'
import { createContext, useContext } from 'react'
import { createStore, useStore } from 'zustand'
import { persist } from 'zustand/middleware'

export interface AppState {
  config: {
    baseUrl: string
    csrfToken: string
    id: EntityBase['id']
    fieldName: string
    type: EntityBase['type']
    viewMode: string
  }
  error: unknown
  operation: OperationAdd | OperationCopy | OperationDelete | OperationEdit | null
  uoComponents: UoComponentCompoundTypes[]
  set: (values: AppState | Partial<AppState>) => void
  setConfigCsrfToken: (csrfToken: string) => void
  setError: (error: unknown) => void
  setUoComponent: (updatedItem: UoComponentCompoundTypes) => void
}

export type AppStateWithoutSetters = Omit<AppState, 'set' | 'setConfigCsrfToken' | 'setError' | 'setUoComponent'>

export const createAppStore = (name: string, props: AppStateWithoutSetters) => {
  return createStore<AppState>()(persist(
    set => ({
      ...props,
      set: (values: AppState | Partial<AppState>) => set(() => ({ ...values })),
      setConfigCsrfToken: (csrfToken: string) => set(state => ({ config: { ...state.config, csrfToken } })),
      setError: (error: unknown) => set(() => ({ error })),
      setUoComponent: (updatedItem: UoComponentCompoundTypes) => set(state => ({
        uoComponents: state.uoComponents.map(item => item.id === updatedItem.id ? updatedItem : item),
      })),
    }),
    {
      name,
      partialize: () => ({}),
    },
  ))
}

export const AppStoreContext = createContext<ReturnType<typeof createAppStore>>(null as unknown as ReturnType<typeof createAppStore>)

export function useAppStore<T>(selector: (state: AppState) => T): T {
  const store = useContext(AppStoreContext)
  if (!store) throw new Error('Missing AppStoreContext.Provider in the tree')
  return useStore(store, selector)
}
