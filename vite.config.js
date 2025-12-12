import { defineConfig } from 'vite';
import path from 'path';
import removeConsole from 'vite-plugin-remove-console';

export default defineConfig({
  plugins: [
    // Runs in production builds only during transform phase (before esbuild minification)
    // Selectively removes console.log/debug/trace while preserving console.error/warn
    removeConsole({
      includes: ['log', 'debug', 'trace'], // Remove console.log/debug/trace, keep error/warn
    }),
  ],
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
      drop: ['debugger'], // Remove debugger statements only (console handled by plugin)
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
