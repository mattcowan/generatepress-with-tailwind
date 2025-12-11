# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

This is a WordPress child theme for GeneratePress. It's a minimal child theme structure that allows customization of the parent GeneratePress theme without modifying the parent files directly.

## Architecture

- **Parent Theme**: GeneratePress (located in `../generatepress/`)
- **Child Theme Files**:
  - `style.css` - Theme metadata and custom styles
  - `functions.php` - PHP customizations and function overrides
  - `screenshot.png` - Theme preview image

## Development Environment

- **Server**: WAMP64 (Windows, Apache, MySQL, PHP)
- **PHP Version**: 8.4.0
- **WordPress Path**: `c:\wamp64\www\wplayground\`

## Key Principles

- All custom PHP code goes in `functions.php`
- Custom styles go in `style.css` (automatically enqueued as child theme stylesheet)
- Template files from parent theme can be overridden by copying them to this directory
- Only edit `functions.php` if you have direct server access (to fix errors if they happen)

## WordPress Child Theme Pattern

When adding functionality:
1. Use WordPress hooks (actions/filters) to modify parent theme behavior
2. Enqueue additional scripts/styles via `wp_enqueue_scripts` hook
3. Override parent templates by creating template files with same name in child theme directory
4. Test changes on local WAMP server before deploying
