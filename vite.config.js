import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import { resolve } from 'node:path'

// https://vitejs.dev/config/
export default defineConfig({
  base: '/innomis/',
  plugins: [react()],
  server: {
    proxy: {
      '/.admin': {
        target: 'http://localhost/innomis.tr',
        changeOrigin: true,
      },
      // API istekleri vs. gerekiyorsa buraya eklenebilir
    },
  },
  build: {
    manifest: true,
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'index.html'),
        admin: resolve(__dirname, 'src/admin/main.jsx'),
      },
    },
  },
})
