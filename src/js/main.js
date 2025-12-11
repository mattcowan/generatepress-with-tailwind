/**
 * Main JavaScript entry point for GeneratePress Child Theme
 *
 * This file is compiled by Vite and output to dist/
 * Add your custom JavaScript here
 */

// Import the CSS file so Vite processes it
import '../css/main.css';

// ========================================
// Block Scripts
// ========================================
// Import block-specific JavaScript modules here
// example: import { initExampleBlock } from '../blocks/example-block/script.js';

/* To add more blocks, follow this pattern:
 * import { initYourBlock } from '../blocks/your-block-name/script.js';
 */

// ========================================
// Initialize on DOM Ready
// ========================================
document.addEventListener('DOMContentLoaded', () => {
  console.log('GeneratePress Child Theme - JavaScript loaded');

  // Initialize all blocks
  // example: initExampleBlock();

  // Add more block initializers here:
  // initYourBlock();

  // Your custom global JavaScript code here
});
