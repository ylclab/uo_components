import react from '@vitejs/plugin-react'
import { defineConfig } from 'vite'

import { fileURLToPath } from 'url'

export default defineConfig(({ mode }) => ({
  build: {
    sourcemap: mode === 'development',
    outDir: 'dist',
    rollupOptions: {
      input: 'src/main.tsx',
      output: {
        format: 'iife',
        entryFileNames: 'uo-components.js',
      },
    },
  },
  logLevel: 'error',
  plugins: [
    react({
      babel: {
        plugins: ['babel-plugin-react-compiler'],
      },
    }),
  ],
  resolve: {
    alias: [
      {
        find: '@',
        replacement: fileURLToPath(new URL('./src', import.meta.url)),
      },
    ],
  },
  publicDir: false,
}))
