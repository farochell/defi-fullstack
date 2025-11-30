import {fileURLToPath, URL} from 'node:url'

import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueJsx(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
  server: {
    host: '0.0.0.0',
    port: 5173,
    strictPort: true,
    allowedHosts: [
      'app.defifullstack.com',
      'localhost',
      '.defifullstack.com' // Autorise tous les sous-domaines
    ],
    hmr: {
      clientPort: 443,
      protocol: 'wss',
      host: 'app.defifullstack.com'
    },
    watch: {
      usePolling: true // Utile pour Docker
    }
  }
})
