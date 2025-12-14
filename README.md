# GeneratePress Child Theme with Tailwind CSS

A modern WordPress child theme for GeneratePress with Tailwind CSS v4, Vite build tooling, and support for custom Gutenberg blocks.

## Features

- ⚡ **Vite Build System** - Fast development builds and optimized production output
- 🎨 **Tailwind CSS v4** - Modern utility-first CSS framework with JIT mode
- 📦 **Single File Bundles** - All styles and scripts bundled into single CSS and JS files
- 🧩 **Block-Ready Structure** - Organized directory structure for custom Gutenberg blocks
- 🔥 **Automatic Rebuilds** - File watching with automatic rebuilds during development
- 💅 **SCSS-Style Nesting** - Modern CSS nesting support
- 🚀 **Cache-Busted Assets** - Automatic hash-based versioning

## Requirements

- **WordPress**: 5.0 or higher
- **GeneratePress Theme**: Must be installed as parent theme
- **Node.js**: 18.x or higher
- **npm**: 9.x or higher
- **PHP**: 8.0 or higher
  > **Note:** PHP 8.0+ is required due to the use of modern language features such as `str_contains()` in the theme code. If you are running WordPress on PHP 7.4, you will need to update your PHP version or refactor the theme code to use PHP 7.4-compatible functions.

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
| `npm run dev` | Start Vite dev server with Hot Module Replacement (HMR) |
| `npm run watch` | Watch and rebuild assets on change (without HMR) |
| `npm run build` | Build optimized production assets |
| `npm run verify` | Verify build output and manifest |
| `npm run build:verify` | Build and verify in one step |
| `npm run preview` | Preview production build locally |

### Development Workflow

#### Option 1: Hot Module Replacement (Recommended)

Experience instant browser updates without page refreshes:

1. **Start the dev server:**
   ```bash
   npm run dev
   ```

2. **Make changes** to your code in the `src/` directory:
   - `src/css/main.css` - Main stylesheet with Tailwind imports
   - `src/css/blocks/` - Block-specific styles
   - `src/js/main.js` - Main JavaScript entry point
   - `src/js/blocks/` - Block-specific scripts

3. **See instant updates** - Changes appear immediately in your browser without refreshing

4. **Build for production** when ready:
   ```bash
   npm run build
   ```

**How it works:** When `npm run dev` is running, WordPress automatically detects the Vite dev server on port 3000 and loads assets from it. If the dev server isn't running, WordPress gracefully falls back to loading the built assets from the `dist/` folder.

#### Option 2: Build Watch Mode

Traditional build approach with automatic rebuilds:

1. **Start watch mode:**
   ```bash
   npm run watch
   ```

2. **Make changes** to your code - Assets rebuild automatically

3. **Refresh browser** to see changes (page refresh required)

**When to use:** Use this mode if you experience port conflicts or prefer the traditional build workflow.

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
- ✅ JavaScript minification with esbuild
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

### Hot Module Replacement Issues

**Problem**: HMR not working / changes not appearing instantly

**Solution**:
1. Check if dev server is running: You should see "Vite dev server detected" in WordPress admin
2. Check the dev server output for errors
3. Verify port 3000 isn't blocked by firewall
4. If port 3000 is busy, Vite will use the next available port - update `functions/dev-assets.php` to match

**Fallback**: If HMR isn't working, the theme automatically falls back to using built assets from `dist/`. Use `npm run watch` instead for auto-rebuilding.

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

1. Check if using dev server (`npm run dev`) or built assets
2. If using dev server: Changes should be instant
3. If using built assets:
   - Clear WordPress cache
   - Hard refresh browser (Ctrl+Shift+R / Cmd+Shift+R)
   - Rebuild assets: `npm run build`
   - Check that `dist/` directory has new files

### Dev Server Not Starting

```bash
# Kill any existing Vite processes
# Windows:
taskkill /F /IM node.exe

# macOS/Linux:
pkill -f vite

# Restart dev server
npm run dev
```

### Development Environment Detection

The Vite dev server and HMR features are automatically enabled when **any** of these conditions are met:

- `WP_DEBUG` is set to `true`, OR
- `WP_LOCAL_DEV` constant is set to `true`, OR
- `WP_ENVIRONMENT_TYPE` is set to anything except `'production'`, OR
- Running on `localhost` / `127.0.0.1` / `::1`, OR
- Running on local development TLDs (`.local`, `.test`, `.dev`, `.localhost`), OR
- Running on private IP ranges (`192.168.x.x`, `10.x.x.x`, `172.16-31.x.x`), OR
- Hostname resolves to `127.0.0.1` (works with custom hosts file entries), OR
- Custom filter `generatepress_child_is_dev_environment` returns `true`

**Custom Hostnames:** If you use a custom hostname like `mysite.custom` in your hosts file pointing to localhost, it will be automatically detected as a dev environment.

**Manual Override:** You can force dev mode by adding this to your theme's `functions.php` or a plugin:
```php
add_filter('generatepress_child_is_dev_environment', '__return_true');
```

**Production Protection:** In true production environments (public domains, production servers), the theme always uses built assets from `dist/`, ignoring the dev server even if it's running. This is automatic and requires no configuration.

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
