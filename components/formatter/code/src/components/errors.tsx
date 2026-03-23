import { Alert, Snackbar } from '@mui/material'
import { useMemo } from 'react'

import { useAppStore } from '@/store'

export function Errors() {
  const error = useAppStore(state => state.error)
  const setError = useAppStore(state => state.setError)

  const message = useMemo(() => {
    if (error instanceof Error) {
      if (error.message === 'name: This value should not be null.') {
        return 'Administrative title is required.'
      }

      return error.message
    }
    if (typeof error === 'string') return error
    return 'An unknown error occurred.'
  }, [error])

  return (
    <Snackbar
      open={!!error}
      autoHideDuration={6000}
      onClose={() => setError(null)}
    >
      <Alert severity="error">{message}</Alert>
    </Snackbar>
  )
}
