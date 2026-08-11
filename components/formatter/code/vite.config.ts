import { fileURLToPath } from 'node:url'
import babel from '@rolldown/plugin-babel'
import react, { reactCompilerPreset } from '@vitejs/plugin-react'
import { defineConfig } from 'vite'

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
    babel({
      presets: [reactCompilerPreset()],
    }),
    react(),
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
