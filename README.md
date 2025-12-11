# GeneratePress Child Theme with Tailwind CSS

A modern WordPress child theme for GeneratePress with Tailwind CSS v4, Vite build tooling, and support for custom Gutenberg blocks.

## Features

- ⚡ **Vite Build System** - Lightning-fast development and optimized production builds
- 🎨 **Tailwind CSS v4** - Modern utility-first CSS framework with JIT mode
- 📦 **Zero Extra HTTP Requests** - All styles and scripts bundled into single files
- 🧩 **Block-Ready Structure** - Organized directory structure for custom Gutenberg blocks
- 🔥 **Automatic Rebuilds on Save** - Fast file watching and rebuilds during development
- 🎯 **Minimal Bundle Size** - ~5KB CSS + ~0.5KB JS (gzipped)
- 💅 **SCSS-Style Nesting** - Modern CSS nesting support
- 🚀 **Cache-Busted Assets** - Automatic hash-based versioning

## Requirements

- **WordPress**: 5.0 or higher
- **GeneratePress Theme**: Must be installed as parent theme
- **Node.js**: 18.x or higher
- **npm**: 9.x or higher
- **PHP**: 7.4 or higher

## Installation

1. **Clone or download** this repository into your WordPress themes directory:
   ```bash
   cd wp-content/themes/
   git clone <your-repo-url> generatepress_child
   ```

2. **Install dependencies:**
   ```bash
   cd generatepress_child
   npm install
   ```

3. **Build assets:**
   ```bash
   npm run build
   ```

4. **Activate the theme** in WordPress Admin → Appearance → Themes

## Development

### Commands

| Command | Description |
|---------|-------------|
| `npm run watch` | Watch and rebuild assets on change |
| `npm run build` | Build optimized production assets |
| `npm run preview` | Preview production build locally |

### Development Workflow

1. **Start watch mode:**
   ```bash
   npm run watch
   ```

2. **Make changes** to your code in the `src/` directory:
   - `src/css/main.css` - Main stylesheet with Tailwind imports
   - `src/css/blocks/` - Block-specific styles
   - `src/js/main.js` - Main JavaScript entry point
   - `src/js/blocks/` - Block-specific scripts

3. **Build for production** when ready:
   ```bash
   npm run build
   ```

## Project Structure

```
generatepress_child/
├── dist/                      # Compiled assets (auto-generated)
│   ├── main.[hash].js        # Compiled JavaScript
│   ├── style.[hash].css      # Compiled CSS
│   └── .vite/
│       └── manifest.json     # Asset manifest for WordPress
│
├── src/                      # Source files
│   ├── css/
│   │   ├── main.css         # Main stylesheet (Tailwind + custom)
│   │   └── blocks/          # Block-specific styles
│   │       └── example-block.css
│   └── js/
│       ├── main.js          # Main JavaScript entry point
│       └── blocks/          # Block-specific scripts
│           └── example-block.js
│
├── functions.php             # Theme functions (enqueues assets)
├── style.css                 # Theme header (required by WordPress)
├── screenshot.png            # Theme screenshot
│
├── vite.config.js           # Vite build configuration
├── tailwind.config.js       # Tailwind CSS configuration
├── postcss.config.js        # PostCSS configuration
├── package.json             # Node dependencies and scripts
│
├── LICENSE                  # GNU GPL v2 license
└── README.md                # This file
```

## Using Tailwind CSS

### Utility Classes in Templates

Use Tailwind utility classes directly in your PHP templates or custom blocks:

```php
<div class="bg-blue-500 text-white p-4 rounded-lg">
  <h2 class="text-2xl font-bold mb-2">Hello World</h2>
  <p class="text-gray-100">This uses Tailwind utility classes.</p>
</div>
```

### Custom Styles with theme() Function

In your CSS files, use Tailwind's `theme()` function for consistency:

```css
.custom-component {
  background-color: theme(colors.blue.500);
  padding: theme(spacing.4);
  border-radius: theme(borderRadius.lg);

  &:hover {
    background-color: theme(colors.blue.700);
  }
}
```

### Configuration

Customize Tailwind in `tailwind.config.js`:

```js
export default {
  content: [
    './**/*.php',
    './src/**/*.{js,jsx,ts,tsx}',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#your-color',
      },
    },
  },
}
```

## Creating Custom Blocks

### 1. Create Block Files

**src/css/blocks/my-custom-block.css:**
```css
.wp-block-my-custom-block {
  padding: theme(spacing.6);
  background-color: theme(colors.gray.100);

  &__title {
    font-size: theme(fontSize.xl);
    font-weight: theme(fontWeight.bold);
  }
}
```

**src/js/blocks/my-custom-block.js:**
```js
export function initMyCustomBlock() {
  if (import.meta.env.DEV) {
    console.log('My Custom Block initialized');
  }

  const blocks = document.querySelectorAll('.wp-block-my-custom-block');

  blocks.forEach((block) => {
    // Your block initialization code
    if (import.meta.env.DEV) {
      console.log('Block initialized:', block);
    }
  });
}
```

### 2. Import Block Assets

**In src/css/main.css:**
```css
@import "./blocks/my-custom-block.css";
```

**In src/js/main.js:**
```js
import { initMyCustomBlock } from './blocks/my-custom-block.js';

document.addEventListener('DOMContentLoaded', () => {
  initMyCustomBlock();
});
```

### 3. Rebuild Assets

```bash
npm run build
```

## Asset Management

### How It Works

1. **Development**: Vite watches your `src/` files and rebuilds on changes
2. **Production**: `npm run build` creates optimized, hashed files in `dist/`
3. **WordPress**: `functions.php` reads the Vite manifest and enqueues the correct files
4. **Caching**: Hash-based filenames ensure browsers always get the latest version

### Manifest System

The `dist/.vite/manifest.json` file maps source files to their compiled versions:

```json
{
  "src/js/main.js": {
    "file": "main.DOiXmDy8.js",
    "css": ["style.DikvEghY.css"]
  }
}
```

WordPress uses this manifest to enqueue the correct files automatically.

## Performance

### Optimization Features

- ✅ Tailwind JIT mode (only used utilities included)
- ✅ CSS minification with Lightning CSS
- ✅ JavaScript minification with Terser
- ✅ Tree-shaking removes unused code
- ✅ Hash-based cache busting
- ✅ Single CSS + single JS file (no extra requests)

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- CSS nesting support (native or via PostCSS)
- ES6+ JavaScript

For older browser support, consider adding the `@vitejs/plugin-legacy` polyfills.

## Customization

### Modifying Parent Theme Styles

Override parent theme styles in `src/css/main.css`:

```css
/* Override GeneratePress defaults */
.site-header {
  background-color: theme(colors.slate.900);
}

.main-navigation a {
  color: theme(colors.white);
}
```

### Adding WordPress Hooks

Add custom functionality in `functions.php`:

```php
// Example: Add custom post type
function my_custom_post_type() {
  register_post_type('portfolio', [
    'labels' => ['name' => 'Portfolio'],
    'public' => true,
    'has_archive' => true,
  ]);
}
add_action('init', 'my_custom_post_type');
```

## Troubleshooting

### Build Errors

**Problem**: "Vite manifest not found"
```bash
npm run build
```

**Problem**: Node modules not found
```bash
rm -rf node_modules package-lock.json
npm install
```

### Styles Not Updating

1. Clear WordPress cache
2. Hard refresh browser (Ctrl+Shift+R / Cmd+Shift+R)
3. Rebuild assets: `npm run build`
4. Check that `dist/` directory has new files

### Watch Mode Not Working

```bash
# Kill any existing Vite processes
pkill -f vite

# Restart watch mode
npm run watch
```

## Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This theme is licensed under the GNU General Public License v2 or later.

See [LICENSE](LICENSE) file for details.

## Credits

- **GeneratePress** - Parent theme by Tom Usborne
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Next generation frontend tooling

## Support

For issues and questions:

- **Theme Issues**: Open an issue on GitHub
- **GeneratePress Support**: https://generatepress.com/support/
- **Tailwind CSS Docs**: https://tailwindcss.com/docs
- **Vite Docs**: https://vitejs.dev/

## Changelog

### 1.0.0
- Initial release
- Vite build system integration
- Tailwind CSS v4 support
- Block-ready structure
- Modern development workflow
