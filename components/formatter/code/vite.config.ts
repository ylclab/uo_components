import react from '@vitejs/plugin-react'
import { defineConfig } from 'vite'
import { viteSingleFile } from 'vite-plugin-singlefile'

import { fileURLToPath } from 'url'

export default defineConfig({
  build: {
    outDir: 'dist',
    lib: {
      entry: 'src/main.tsx',
      formats: ['cjs'],
      fileName: () => 'uo-components.js',
    },
  },
  define: {
    'process.env': {},
  },
  plugins: [
    react({
      babel: {
        plugins: ['babel-plugin-react-compiler'],
      },
    }),
    viteSingleFile(),
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
})
