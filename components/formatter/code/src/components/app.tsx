import { StyledEngineProvider, ThemeProvider } from '@mui/material'
import { useMemo } from 'react'

import { Errors } from '@/components/errors'
import { Field } from '@/components/field'
import { type AppStateWithoutSetters, AppStoreContext, createAppStore } from '@/store'
import { theme } from '@/theme'

export interface AppProps {
  name: string
  state: AppStateWithoutSetters
}

export function App({ name, state }: AppProps) {
  const store = useMemo(() => createAppStore(name, state), [name, state])

  return (
    <AppStoreContext.Provider value={store}>
      <StyledEngineProvider injectFirst>
        <ThemeProvider theme={theme} defaultMode="system">
          <Field />
          <Errors />
        </ThemeProvider>
      </StyledEngineProvider>
    </AppStoreContext.Provider>
  )
}
