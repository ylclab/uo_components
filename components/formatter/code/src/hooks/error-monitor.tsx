import { useEffect } from 'react'

import { useAppStore } from '@/store'

export function useErrorMonitor(): void {
  const error = useAppStore(state => state.error)
  const setError = useAppStore(state => state.setError)

  useEffect(() => {
    if (error) {
      setError(null)
      // eslint-disable-next-line no-console
      console.error(error)
    }
  }, [error, setError])
}
