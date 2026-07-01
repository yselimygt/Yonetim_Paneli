import { existsSync, mkdirSync, copyFileSync, readFileSync, readdirSync } from 'node:fs';
import { dirname, join, basename } from 'node:path';
import { fileURLToPath } from 'node:url';

const rootDir = dirname(dirname(fileURLToPath(import.meta.url)));
const manifestPath = join(rootDir, 'dist', '.vite', 'manifest.json');
const adminAssetsDir = join(rootDir, '.admin', 'public', 'assets', 'admin');

function ensureDir(path) {
  mkdirSync(path, { recursive: true });
}

function copyManifestEntry() {
  if (!existsSync(manifestPath)) {
    console.warn('[copy-admin-assets] dist/.vite/manifest.json bulunamadi, admin bundle kopyalanmadi.');
    return;
  }

  const manifest = JSON.parse(readFileSync(manifestPath, 'utf-8'));
  const entry = manifest['src/admin/main.jsx'];

  if (!entry) {
    console.warn('[copy-admin-assets] manifest icinde src/admin/main.jsx girdisi yok.');
    return;
  }

  ensureDir(adminAssetsDir);

  const filesToCopy = new Set([entry.file, ...(entry.css || [])].filter(Boolean));

  function collectImports(imports) {
    if (!imports) return;
    for (const imp of imports) {
      const chunk = manifest[imp];
      if (chunk) {
        if (chunk.file) filesToCopy.add(chunk.file);
        if (chunk.css) chunk.css.forEach(c => filesToCopy.add(c));
        collectImports(chunk.imports);
      }
    }
  }
  
  collectImports(entry.imports);

  for (const file of filesToCopy) {
    const from = join(rootDir, 'dist', file);
    const to = join(adminAssetsDir, basename(file));
    copyFileSync(from, to);
    console.log(`[copy-admin-assets] ${file} -> .admin/public/assets/admin/${basename(file)}`);
  }
}

function copyBrandAssets() {
  const brandFiles = [
    ['public/assets/logo/Yatay-Beyaz.png', '.admin/public/assets/logo/Yatay-Beyaz.png'],
    ['public/assets/sponsor/ViperaDev.png', '.admin/public/assets/sponsor/ViperaDev.png'],
  ];

  for (const [from, to] of brandFiles) {
    const fromPath = join(rootDir, from);
    const toPath = join(rootDir, to);
    if (!existsSync(fromPath)) {
      console.warn(`[copy-admin-assets] ${from} bulunamadi, atlandi.`);
      continue;
    }
    ensureDir(dirname(toPath));
    copyFileSync(fromPath, toPath);
    console.log(`[copy-admin-assets] ${from} -> ${to}`);
  }
}

copyManifestEntry();
copyBrandAssets();
