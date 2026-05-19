import { Stack, type StackProps } from '@mui/material'

import { Field } from '@/components/field'
import { Message } from '@/components/message'
import { useSessionMonitor } from '@/hooks/session-monitor'

export function App(props: StackProps) {
  useSessionMonitor()
  return (
    <Stack {...props}>
      <Message sx={{ alignSelf: 'center' }} />
      <Field />
    </Stack>
  )
}
