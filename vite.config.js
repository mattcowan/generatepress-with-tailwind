import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  build: {
    // Output to dist directory
    outDir: 'dist',
    // Don't empty the directory (in case of other files)
    emptyOutDir: true,
    // Generate manifest for WordPress
    manifest: true,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'src/js/main.js'),
        style: path.resolve(__dirname, 'src/css/main.css'),
      },
      output: {
        // Output files with hash for cache busting
        entryFileNames: '[name].[hash].js',
        chunkFileNames: '[name].[hash].js',
        assetFileNames: '[name].[hash].[ext]',
      },
    },
    // Minify for production using esbuild (default, faster than terser)
    minify: 'esbuild',
    esbuildOptions: {
      drop: ['console', 'debugger'], // Remove console.* and debugger statements in production
    },
    // Source maps for debugging
    sourcemap: false,
  },
  css: {
    postcss: './postcss.config.js',
  },
  // Dev server settings (optional, for HMR)
  server: {
    host: 'localhost',
    port: 3000,
    strictPort: false,
    cors: true,
  },
});
