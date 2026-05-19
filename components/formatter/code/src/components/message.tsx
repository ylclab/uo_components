import { Alert, type AlertProps, Snackbar } from '@mui/material'

import { useAppStore } from '@/store'

export function Message(props: AlertProps) {
  const message = useAppStore(state => state.message)
  const setMessage = useAppStore(state => state.setMessage)

  if (!message) return null

  if (message.toast) return (
    <Snackbar
      open={!!message}
      onClose={() => setMessage(null)}
      anchorOrigin={{ vertical: 'top', horizontal: 'center' }}
      autoHideDuration={6000}
    >
      <Alert
        {...props}
        severity={message?.severity ?? 'success'}
        onClose={() => setMessage(null)}
      >
        {message?.text}
      </Alert>
    </Snackbar>
  )

  return (
    <Alert
      {...props}
      severity={message?.severity ?? 'success'}
      onClose={() => setMessage(null)}
    >
      {message?.text}
    </Alert>
  )
}
