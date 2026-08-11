import type { EntityBase } from '@ylclab/drupal.core'
import { createContext, useContext } from 'react'
import { createStore, useStore } from 'zustand'
import { persist } from 'zustand/middleware'

export interface AppState {
  config: {
    baseUrl: string
    csrfToken?: string
    id: EntityBase['id']
    fieldName: string
    type: EntityBase['type']
    viewMode: string
  }
  message: { severity: 'success' | 'error'; text: string; toast?: boolean } | null
  set: (values: AppState | Partial<AppState>) => void
  setConfigCsrfToken: (csrfToken: string) => void
  setMessage: (message: { severity: 'success' | 'error'; text: string; toast?: boolean } | null) => void
}

export type AppStateWithoutSetters = Omit<AppState, 'set' | 'setConfigCsrfToken' | 'setMessage'>

export const createAppStore = (name: string, props: AppStateWithoutSetters) => {
  return createStore<AppState>()(
    persist(
      (set) => ({
        ...props,
        set: (values: AppState | Partial<AppState>) => set(() => ({ ...values })),
        setConfigCsrfToken: (csrfToken: string) => set((state) => ({ config: { ...state.config, csrfToken } })),
        setMessage: (message: { severity: 'success' | 'error'; text: string; toast?: boolean } | null) => set(() => ({ message })),
      }),
      {
        name,
        partialize: () => ({}),
      },
    ),
  )
}

export const AppStoreContext = createContext<ReturnType<typeof createAppStore>>(null as unknown as ReturnType<typeof createAppStore>)

export function useAppStore<T>(selector: (state: AppState) => T): T {
  const store = useContext(AppStoreContext)
  if (!store) throw new Error('Missing AppStoreContext.Provider in the tree')
  return useStore(store, selector)
}
