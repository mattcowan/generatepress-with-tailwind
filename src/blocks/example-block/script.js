/**
 * Example Block JavaScript
 *
 * This file contains JavaScript specific to the example block.
 * It will be bundled into the main JS file by Vite.
 */

export function initExampleBlock() {
  const blocks = document.querySelectorAll('.wp-block-example-block');

  blocks.forEach((block) => {
    // Example: Add click handler
    block.addEventListener('click', (e) => {
      console.log('Example block clicked', e.target);
    });

    // Example: Initialize any interactive features
    const title = block.querySelector('.wp-block-example-block__title');
    if (title) {
      title.setAttribute('data-initialized', 'true');
    }
  });

  console.log(`Initialized ${blocks.length} example blocks`);
}
