/**
 * Build verification script
 * Checks that the Vite manifest includes required compiled assets
 * Run with: node verify-build.js
 */

import { readFileSync, existsSync } from 'fs';
import { fileURLToPath } from 'url';
import { dirname, join } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const manifestPath = join(__dirname, 'dist', '.vite', 'manifest.json');
const requiredAssets = ['src/js/main.js', 'src/css/main.css'];

console.log('🔍 Verifying Vite build...\n');

// Check if manifest exists
if (!existsSync(manifestPath)) {
    console.error('❌ Manifest file not found at:', manifestPath);
    console.error('   Run "npm run build" first.\n');
    process.exit(1);
}

// Read and parse manifest
let manifest;
try {
    const manifestContent = readFileSync(manifestPath, 'utf8');
    manifest = JSON.parse(manifestContent);
} catch (error) {
    console.error('❌ Failed to parse manifest.json:', error.message, '\n');
    process.exit(1);
}

// Check required assets
let allValid = true;
for (const asset of requiredAssets) {
    if (!manifest[asset]) {
        console.error(`❌ Missing asset in manifest: ${asset}`);
        allValid = false;
    } else {
        const compiledFile = manifest[asset].file;
        console.log(`✅ ${asset}`);
        console.log(`   → ${compiledFile}\n`);
    }
}

// Summary
if (allValid) {
    console.log('✅ All required assets are present in manifest!\n');
    process.exit(0);
} else {
    console.error('❌ Build verification failed.\n');
    process.exit(1);
}
