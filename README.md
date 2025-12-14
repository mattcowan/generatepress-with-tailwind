# GeneratePress Child Theme with Tailwind CSS

A modern WordPress child theme for GeneratePress with Tailwind CSS v4, Vite build tooling, Hot Module Replacement, and support for custom Gutenberg blocks.

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Development](#development)
  - [Commands](#commands)
  - [Development Workflow](#development-workflow)
- [Project Structure](#project-structure)
- [Using Tailwind CSS](#using-tailwind-css)
- [Creating Custom Blocks](#creating-custom-blocks)
- [Asset Management](#asset-management)
- [Performance](#performance)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)
- [FAQ](#faq)
- [Contributing](#contributing)
- [License](#license)
- [Credits](#credits)
- [Changelog](#changelog)

## Features

- ⚡ **Vite Build System** - Fast development builds and optimized production output
- 🔥 **Hot Module Replacement (HMR)** - Instant browser updates without page refresh during development
- 🎨 **Tailwind CSS v4** - Modern utility-first CSS framework with JIT mode
- 📦 **Single File Bundles** - All styles and scripts bundled into single CSS and JS files
- 🧩 **Block-Ready Structure** - Organized directory structure for custom Gutenberg blocks
- 🔄 **Dual Development Modes** - Choose between HMR or traditional build watch mode
- 💅 **SCSS-Style Nesting** - Modern CSS nesting support
- 🚀 **Cache-Busted Assets** - Automatic hash-based versioning
- 🛡️ **Smart Environment Detection** - Automatically enables dev mode on localhost environments
- 🔒 **Production-Safe** - Dev features automatically disabled on production servers

## Requirements

- **WordPress**: 5.0 or higher
- **GeneratePress Theme**: Must be installed as parent theme
- **Node.js**: 18.x or higher
- **npm**: 9.x or higher
- **PHP**: 8.0 or higher
  > **Note:** PHP 8.0+ is required due to the use of modern language features such as `str_contains()` in the theme code. If you are running WordPress on PHP 7.4, you will need to update your PHP version or refactor the theme code to use PHP 7.4-compatible functions.

## Installation

### Prerequisites

Verify you have the required tools installed:

```bash
node --version  # Should be 18.x or higher
npm --version   # Should be 9.x or higher
php --version   # Should be 8.0 or higher
```

### Setup Steps

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

### Verify Installation

After activation, check your site's page source. You should see references to the compiled assets:

```html
<link rel='stylesheet' href='.../dist/style.[hash].css' />
<script src='.../dist/main.[hash].js'></script>
```

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
├── functions/                # PHP function modules
│   ├── prod-assets.php      # Production asset loading (manifest-based)
│   └── dev-assets.php       # Development HMR and dev server detection
│
├── src/                      # Source files
│   ├── css/
│   │   ├── main.css         # Main stylesheet (Tailwind + custom)
│   │   └── blocks/          # Block-specific styles (add your blocks here)
│   └── js/
│       ├── main.js          # Main JavaScript entry point
│       └── blocks/          # Block-specific scripts (add your blocks here)
│
├── functions.php             # Main theme functions (loads modules conditionally)
├── style.css                 # Theme header (required by WordPress)
├── screenshot.png            # Theme screenshot
│
├── vite.config.js           # Vite build configuration with HMR support
├── tailwind.config.js       # Tailwind CSS configuration
├── postcss.config.js        # PostCSS configuration
├── package.json             # Node dependencies and scripts
├── verify-build.js          # Build verification script
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

The theme uses a **dual-mode asset loading system** that automatically switches between development and production:

#### Development Mode (with HMR)

When running `npm run dev`:

1. **Vite dev server** runs on `localhost:3000`
2. **WordPress detects** the dev server automatically (via `functions/dev-assets.php`)
3. **Assets load directly** from `http://localhost:3000/src/js/main.js`
4. **HMR enabled** - Changes appear instantly without page refresh
5. **Console logs preserved** - `import.meta.env.DEV` checks work correctly

#### Production Mode (or when dev server is off)

When dev server isn't running:

1. **WordPress uses manifest** from `dist/.vite/manifest.json`
2. **Assets load** from `dist/main.[hash].js` and `dist/style.[hash].css`
3. **Console logs removed** - Build optimization active
4. **Cache-busted** - Hash-based filenames prevent stale caches

### Environment Detection

The theme automatically detects your environment using these checks (in order):

1. **WordPress Constants**
   - `WP_DEBUG = true`
   - `WP_LOCAL_DEV = true`
   - `WP_ENVIRONMENT_TYPE != 'production'`

2. **Hostname Checks**
   - `localhost`, `127.0.0.1`, `::1`
   - TLDs: `.local`, `.test`, `.dev`, `.localhost`
   - Private IPs: `192.168.x.x`, `10.x.x.x`, `172.16-31.x.x`
   - **Hostname resolution**: Any hostname that resolves to `127.0.0.1` (via IPv4 lookup using `gethostbyname()`). Note: Hostnames that only resolve to IPv6 (`::1`) or if resolution fails/timeouts may not be detected.

3. **Filter Hook**
   - `generatepress_child_is_dev_environment` filter

**Custom Hostnames**: If you use `wplayground`, `mysite.local`, or any custom hostname in your hosts file that points to localhost, it will be automatically detected.

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

WordPress uses this manifest in production mode to enqueue the correct hashed files.

## Performance

### Optimization Features

- ✅ **Tailwind JIT mode** - Only used utilities included in build
- ✅ **CSS minification** - Lightning CSS for optimal compression
- ✅ **JavaScript minification** - esbuild for fast, efficient minification
- ✅ **Tree-shaking** - Removes unused code automatically
- ✅ **Console removal** - `console.log/debug/trace` stripped in production (keeps error/warn)
- ✅ **Hash-based cache busting** - Filenames change when content changes
- ✅ **Single file bundles** - One CSS + one JS file (minimal HTTP requests)
- ✅ **Smart loading** - Dev assets only loaded in development environments
- ✅ **Transient caching** - Dev server detection cached for performance

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- CSS nesting support (native or via PostCSS)
- ES6+ JavaScript

For older browser support, consider adding the `@vitejs/plugin-legacy` polyfills.

## Deployment

### Production Build

Before deploying to production, build optimized assets:

```bash
npm run build:verify
```

This runs the build and verifies all assets are correctly generated.

### Files to Deploy

Upload these files/folders to your production server:

**Required:**
- `functions/` - PHP function modules
- `dist/` - Compiled assets
- `functions.php` - Main theme file
- `style.css` - Theme header
- `screenshot.png` - Theme preview image

**Do NOT Upload:**
- `node_modules/` - Development dependencies (large, unnecessary)
- `src/` - Source files (only compiled dist/ needed)
- `.git/` - Version control data
- `package.json`, `package-lock.json` - Build configuration
- `vite.config.js`, `tailwind.config.js`, `postcss.config.js` - Build tools
- `verify-build.js` - Development script
- `.gitignore` - Git configuration

### Deployment Checklist

1. ✅ Run `npm run build:verify` locally
2. ✅ Test the built site locally with `npm run preview`
3. ✅ Upload only production files (see list above)
4. ✅ Verify `WP_DEBUG` is `false` on production
5. ✅ Clear WordPress cache after deployment
6. ✅ Test site functionality on production server
7. ✅ Verify assets load correctly (check browser Network tab)

### Environment Variables

The theme automatically detects production environments and disables dev features. No configuration needed, but ensure your production server doesn't match dev detection criteria (localhost, .local domains, etc.).

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
1. Check if dev server is running: You should see a notice "Vite dev server detected" in the WordPress admin area
2. Check the dev server output for errors
3. Verify port 3000 isn't blocked by firewall
4. If port 3000 is busy, Vite will use the next available port – update both `functions/dev-assets.php` and `vite.config.js` (see line 49) to match the port.

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

## FAQ

### General Questions

**Q: Do I need to rebuild after every change?**
A: No! Use `npm run dev` for Hot Module Replacement. Changes appear instantly in your browser without refreshing. Only use `npm run build` when you're ready to deploy.

**Q: What's the difference between `npm run dev` and `npm run watch`?**
A: `npm run dev` starts a dev server with Hot Module Replacement (instant updates). `npm run watch` builds files on change but requires page refresh. Use `dev` for the best experience.

**Q: Can I use this theme with WP_DEBUG set to false?**
A: Yes! The theme detects local development environments automatically (localhost, custom hosts file entries, private IPs). You don't need `WP_DEBUG = true` for HMR to work locally.

**Q: Will the dev server work on my live site?**
A: No. The dev features only activate on localhost/development environments. Your production site will always use the optimized built assets from `dist/`, even if someone runs the dev server.

**Q: What if port 3000 is already in use?**
A: Vite will automatically try the next available port (3001, 3002, etc). However, WordPress looks for port 3000 by default. Either free up port 3000 or update `functions/dev-assets.php` to match the port Vite chose.

### Tailwind CSS Questions

**Q: Can I use this with page builders like Elementor or Beaver Builder?**
A: Yes, but you may need to add their template files to the `content` array in `tailwind.config.js` so Tailwind knows to include those classes.

**Q: How do I add custom Tailwind colors/fonts?**
A: Edit `tailwind.config.js` and add them to the `theme.extend` section. See [Tailwind Configuration](#configuration) above.

**Q: What if I don't want Tailwind?**
A: You can remove it by:
1. Removing `@import 'tailwindcss'` from `src/css/main.css`
2. Uninstalling `@tailwindcss/postcss` with `npm uninstall @tailwindcss/postcss`
3. Removing Tailwind from `postcss.config.js`

### Troubleshooting Questions

**Q: I see "Vite manifest not found" error**
A: Run `npm run build` to generate the manifest and compiled assets. This error means you activated the theme without building the assets first.

**Q: My changes aren't appearing on the site**
A: Check:
1. Is `npm run dev` running? If yes, changes should be instant.
2. Is WordPress loading from dev server? Check browser Network tab for `localhost:3000` requests.
3. Try clearing WordPress cache and hard refreshing browser (Ctrl+Shift+R).

**Q: Console logs appear in production**
A: This shouldn't happen if you ran `npm run build`. The plugin removes `console.log/debug/trace` during builds. Verify you uploaded the `dist/` folder from a fresh build.

**Q: The theme broke my site**
A: The theme requires PHP 8.0+. Check your PHP version. If you have direct server access, you can disable the theme by renaming the theme folder via FTP/SSH.

### Development Questions

**Q: Can I use TypeScript?**
A: Yes! Vite supports TypeScript out of the box. Just create `.ts` files instead of `.js` files and import them. You may want to add type definitions for WordPress.

**Q: How do I add a custom block?**
A: See [Creating Custom Blocks](#creating-custom-blocks) section above for step-by-step instructions.

**Q: Can I use Sass/SCSS instead of CSS?**
A: Vite supports Sass. Install it with `npm install -D sass` and rename your `.css` files to `.scss`. Update imports in `main.js` accordingly.

**Q: How do I debug PHP errors?**
A: Set `WP_DEBUG = true` and `WP_DEBUG_LOG = true` in `wp-config.php`. Errors will be logged to `wp-content/debug.log`. For the asset loading functions, check `functions/prod-assets.php` and `functions/dev-assets.php`.

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

### 1.1.0
- ✨ Added Hot Module Replacement (HMR) support with `npm run dev`
- ✨ Added intelligent dev environment detection (supports custom hostnames)
- ✨ Added organized `/functions` directory structure
- ✨ Separated production and development asset loading logic
- ✨ Added dev server detection with automatic fallback
- ✨ Console logs now preserved in dev mode, removed in production
- 🔧 Fixed `vite-plugin-remove-console` to only run during builds
- 🔧 Enhanced dev environment detection to resolve custom hostnames
- 📝 Added comprehensive HMR documentation
- 🛡️ Improved error handling (removed `@` error suppression)

### 1.0.0
- Initial release
- Vite build system integration
- Tailwind CSS v4 support
- Block-ready structure
- Modern development workflow
