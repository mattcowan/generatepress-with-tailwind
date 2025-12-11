/**
 * Example Block JavaScript
 *
 * This file contains JavaScript specific to the example block.
 * It will be bundled into the main JS file by Vite.
 */

export function initExampleBlock() {
  if (import.meta.env.DEV) {
    console.log('Example Block Works!');
  }
  const blocks = document.querySelectorAll('.wp-block-example-block');

  blocks.forEach((block) => {
    // Example: Add click handler
    block.addEventListener('click', (e) => {
      if (import.meta.env.DEV) {
        console.log('Example block clicked', e.target);
      }
    });

    // Example: Initialize any interactive features
    const title = block.querySelector('.wp-block-example-block__title');
    if (title) {
      title.setAttribute('data-initialized', 'true');
    }
  });

  if (import.meta.env.DEV) {
    console.log(`Initialized ${blocks.length} example blocks`);
  }
}
