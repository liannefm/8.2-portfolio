import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'path'

export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
  },
  server: {
    proxy: {
      '/8.2-portfolio/api': {
        target: 'http://localhost',
        changeOrigin: true,
      },
      '/8.2-portfolio/uploads': {
        target: 'http://localhost',
        changeOrigin: true,
      },
    },
  },
})
