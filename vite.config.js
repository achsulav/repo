import { defineConfig } from 'vite'
import { resolve } from 'path';
export default defineConfig({
  root: '.',
  publicDir: false,
  build: {
    outDir: resolve(__dirname, 'public/dist'),
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'resources/js/main.js'),
        editor: resolve(__dirname, 'resources/js/editor.js')
      }
    }

  },
  server: {
    host: '0.0.0.0',
    port: parseInt(process.env.VITE_PORT) || 5173,
    strictPort: true,
    cors: true,
    origin: process.env.VITE_ORIGIN || 'http://localhost:5173',
    hmr: {
      host: process.env.VITE_HMR_HOST || 'localhost',
      clientPort: parseInt(process.env.VITE_HMR_PORT) || 5173
    }
  },
})
