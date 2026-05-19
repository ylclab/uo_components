import { useSession } from '@ylclab/drupal.core'
import { useEffect, useState } from 'react'

import { useAppStore } from '@/store'

export function useSessionMonitor(): void {
  const baseUrl = useAppStore(state => state.config.baseUrl)
  const setConfigCsrfToken = useAppStore(state => state.setConfigCsrfToken)
  const setMessage = useAppStore(state => state.setMessage)

  const [error, setError] = useState<unknown>(null)

  const { load, token } = useSession({ baseUrl, onError: setError })

  useEffect(() => {
    setConfigCsrfToken(token)
  }, [setConfigCsrfToken, token])

  useEffect(() => {
    if (!error) return

    if (error instanceof Error && error.message.includes('401')) {
      void load()
    }

    setMessage({ severity: 'error', text: error instanceof Error ? error.message : 'An unknown error occurred while checking your session.' })
    // eslint-disable-next-line react-hooks/set-state-in-effect
    setError(null)
  }, [error, load, setMessage])
}
