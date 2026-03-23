import { createTheme } from '@mui/material'
import { LinkBehavior } from '@ylclab/drupal.core'

export const theme = createTheme({
  components: {
    MuiButtonBase: {
      defaultProps: {
        LinkComponent: LinkBehavior,
      },
    },
    MuiLink: {
      defaultProps: {
        component: LinkBehavior,
      },
    },
  },
  shape: { borderRadius: 4 },
  spacing: 8,
})
