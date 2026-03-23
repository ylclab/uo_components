import { useSession } from '@ylclab/drupal.core'
import { useCallback, useEffect } from 'react'

import { useAppStore } from '@/store'

export function useSessionMonitor(): void {
  const baseUrl = useAppStore(state => state.config.baseUrl)
  const error = useAppStore(state => state.error)
  const setConfigCsrfToken = useAppStore(state => state.setConfigCsrfToken)
  const setError = useAppStore(state => state.setError)

  const onError = useCallback((error: unknown) => {
    setError(error)
  }, [setError])

  const { load, token } = useSession({ baseUrl, onError })

  useEffect(() => {
    setConfigCsrfToken(token)
  }, [setConfigCsrfToken, token])

  useEffect(() => {
    if (!error) return
    if (error instanceof Error && error.message.includes('401')) {
      void load()
    }
  }, [error, load, onError])
}
